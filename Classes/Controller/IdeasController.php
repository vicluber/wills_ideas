<?php

namespace Wills\WillsIdeas\Controller;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Wills\WillsIdeas\Domain\Model\Idea;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use Wills\WillsIdeas\Domain\Repository\IdeaRepository;
use TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository;
use Wills\WillsIdeas\Domain\Repository\FrontendUserRepository;
use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\Mailer;
use Wills\WillsIdeas\Property\TypeConverter\UploadedFileReferenceConverter;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;
/***
 *
 * This file is part of the "WillsIdeas" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 
 *
 ***/
/**
 * IdeasController
 */
class IdeasController extends ActionController
{
    /**
     * Inject the idea repository
     *
     * @param Wills\WillsIdeas\Domain\Repository\IdeaRepository $ideaRepository
     */
    private $ideaRepository;

    public function injectIdeaRepository(IdeaRepository $ideaRepository)
    {
        $this->ideaRepository = $ideaRepository;
    }
    /**
     * Inject the category repository
     *
     * @param TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository $categoryRepository
     */
    private $categoryRepository;

    public function injectcategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Inject the FrontendUser repository
     *
     * @param Wills\WillsIdeas\Domain\Repository\FrontendUserRepository $frontendUserRepository
     */
    private $frontendUserRepository;

    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository)
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }

    /**
     * Set TypeConverter option for image upload
     */
    public function initializeCreateAction()
    {
        $this->setTypeConverterConfigurationForImageUpload('idea');
    }

    /**
     * Initializes the view before invoking an action method.
     * Override this method to solve assign variables common for all actions
     * or prepare the view in another way before the action is called.
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view The view to be initialized
     */
    protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
        parent::initializeView($view);
    }
    
    /**
     * This is setting the variables configured on the plugin configuration and is used to point the <f:link.aciton> to the proper page where the necessary plugin has been added.
     */
    public function initializeAction()
    {
       $this->ideasParentCategorie = (int) ($this->settings['ideasParentCategorie'] ?? false);
       $this->ideasUidNewPage = (int) ($this->settings['ideasUidNewPage'] ?? false);
       $this->ideasUidListPage = (int) ($this->settings['ideasUidListPage'] ?? false);
    }

    /**
     *
     */
    protected function setTypeConverterConfigurationForImageUpload($argumentName)
    {
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
            ->registerImplementation(
                \TYPO3\CMS\Extbase\Domain\Model\FileReference::class,
                \Wills\WillsIdeas\Domain\Model\FileReference::class
            );

        $uploadConfiguration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => '1:/user_upload/',
        ];
        /** @var PropertyMappingConfiguration $newExampleConfiguration */
        $newExampleConfiguration = $this->arguments[$argumentName]->getPropertyMappingConfiguration();
        $newExampleConfiguration->forProperty('image')
            ->setTypeConverterOptions(
                UploadedFileReferenceConverter::class,
                $uploadConfiguration
            );
    }

    /**
     * Action for displaying the page and listing the ideas, this page also contains the form to create a new Idea.
     * $ideas contains all ideas filtered by $category. There is a custom findAll method into ideasRepository to filter by category if its set.
     * $currentCategory contains 'all' to not set any filter if $category comes null.
     * $ideasParentCategorie contains the id of the parent category of the ideas to load the select input for the idea category. This must be set in a Flexform on the plugin configuration
     * $newIdea contains and instance of Idea() to set the form for the new idea on the list page.
     * @param Category to filter the select for the new idea form $category
     */
    public function listAction(Category $category = null)
    {
        $ideas = $this->ideaRepository->findAll($category);
        $currentCategory = 'all';
        if($category != null){
            $currentCategory = $category->getTitle();
        }
        $this->view->assign('currentCategory', $currentCategory);
        $ideasParentCategorie = (int) ($this->settings['ideasParentCategorie'] ?? false);
        $categories = $this->categoryRepository->findByParent($ideasParentCategorie);
        $this->view->assign('ideas', $ideas);
        $this->view->assign('categories', $categories);
        $newIdea = new Idea();
        $this->view->assign('newIdea', $newIdea);
    }

    /**
     * Displaying the view with the form for new idea
     * $idea contains an instance of idea() model
     * $ideasParentCategorie contains the id of the parent category of the ideas to load the select input for the idea category. This must be set in a Flexform on the plugin configuration
     * $categories contains a list of the system categories created for ideas filtered by the $ideasParentCategorie
     */
    public function newAction()
    {
        $idea = new Idea();
        $categories = $this->categoryRepository->findByParent($this->ideasParentCategorie);
        $this->view->assign('idea', $idea);
        $this->view->assign('categories', $categories);
    }
    
    /**
     * Creates a new Idea
     * Status in 1 is for defining one of the three status of the idea
     * $pidList holds pid of the storage folder where the idea is gonna be saved in
     * $GLOBALS['TSFE']->fe_user->user['uid'] holds the id of the logged frontend-user
     * $idea->setHidden in true is for hideing the idea as default
     * $_POST['tx_willsideas_ideas']['idea']['category'] holds the id of the parent category that you can define on extension configuration tool in typo3's backend
     * It also sends an email with the title and description to the email defined in the extension configuration.
     * Shows a flash message to let you now about the creating and email
     * And redirects you to the list view.
     *
     * @param Idea $idea The idea to save on db
     * @return void
     */
    public function createAction(Idea $idea)
    {
        $idea->setStatus(1);
        $pidList = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        )['persistence']['storagePid'];
        $idea->setPid($pidList);
        $idea->setUser($GLOBALS['TSFE']->fe_user->user['uid']);
        $idea->setHidden(true);
        $this->ideaRepository->add($idea);
        $this->emailNotification($idea->getTitle(), $idea->getDescription());
        $this->addFlashMessage(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('LLL:EXT:wills_ideas/Resources/Private/Language/locallang.xlf:ideas.creation_success', 'wills_ideas'));
    }

    /**
     * Increment like of the idea
     * $idea contains the model returned by findByUid default method with the parameter $ideaId provided
     * addVotedUsers is a method on Idea's model that attaches a user to the likes. The user in this case is the one returned by $this->frontendUserRepository->findByUid
     * $GLOBALS['TSFE']->fe_user->user['uid'] contains the uid of the logged user
     * Then de idea gets updated and the client gets redirected to listaction
     *
     * @param int Idea UID of the idea to be liked
     * @return void
     */
    public function likeAction(int $ideaId)
    {
        $idea = $this->ideaRepository->findByUid($ideaId);
        $idea->addVotedUsers($this->frontendUserRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']));
        $this->ideaRepository->update($idea);
        $this->redirect('list');
    }

    /**
     * Notification by email of the just created idea
     * $ideasEmailNotification contains the string set on the Flexforms in backend plugin configuration
     * If $ideasEmailNotification doesn't pass the FILTER_VALIDATE_EMAIL filter or is equal to false because has not be set on the plugin the email does not get sent.
     * @param string Title of the idea set on the $this->createAction() method
     * @param string Description of the idea set on the $this->createAction() method
     */
    public function emailNotification($title, $description)
    {
        $ideasEmailNotification = (string) ($this->settings['ideasEmailNotification'] ?? false);
        $GLOBALS['TYPO3_CONF_VARS']['MAIL']['format'] = 'plain';
        if (filter_var($ideasEmailNotification, FILTER_VALIDATE_EMAIL) && $ideasEmailNotification !== false) {
            $this->uriBuilder->setCreateAbsoluteUri(false); //Setting fullUri false to get just local call
            $link = $this->uriBuilder->build();
            $uri = \TYPO3\CMS\Core\Utility\GeneralUtility::locationHeaderUrl($link); //adding the domain to the obtained local call above
            $email = GeneralUtility::makeInstance(FluidEmail::class);
            $email
                ->to($ideasEmailNotification)
                ->from(new Address('ideas@wills.at', 'Ideas modul'))
                ->subject('Eine neue Idee wurde eingereicht: '.$title)
                ->html('Verfasser: '.$GLOBALS['TSFE']->fe_user->user['username'].'<br />Beischreibung der Idee: '.$description);
            GeneralUtility::makeInstance(Mailer::class)->send($email);
        }
    }
}