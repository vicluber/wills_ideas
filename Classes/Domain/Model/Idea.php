<?php

namespace Wills\WillsIdeas\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use Wills\WillsIdeas\Domain\Model\FrontendUser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Wills\WillsIdeas\Domain\Repository\FrontendUserRepository;

class Idea extends AbstractEntity
{
    /**
     * title
     * 
     * @var string
     */
    protected $title = '';

    /**
     * description
     * 
     * @var string
     */
    protected $description = '';

    /**
     * The category of the idea
     *
     * @var Category
     */
    protected $category;

    /**
     * status
     * 
     * @var int
     */
    protected $status = 0;

    /**
     * user
     * 
     * @var int
     */
    protected $user = 0;

    /**
     * likes
     * 
     * @var int
     */
    protected $likesCount = 0;

    /**
     * image
     * 
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $image = null;

    /**
     * @var bool
     */
    protected $hidden;

    /**
     * votedUsers
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<FrontendUser>
     */
    protected $votedUsers;

    /**
     * __construct
     */
    public function __construct() {
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     *
     * @return void
     */
    protected function initStorageObjects() {
        $this->votedUsers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Sets the title
     * 
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Returns the title
     * 
     * @return string $title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the description
     * 
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Returns the description
     * 
     * @return string $description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets the category
     * 
     * @param TYPO3\CMS\Extbase\Domain\Model\Category The category of the idea
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Returns the category of the idea
     *
     * @return TYPO3\CMS\Extbase\Domain\Model\Category The category of the idea
     */
    public function getCategory()
    {
        return $this->category;
    }

    
    /**
     * Sets the status
     * 
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * Returns the status
     * 
     * @return int $status
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the user
     * 
     * @param int $user
     */
    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    /**
     * Returns the user
     * 
     * @return int $user
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * Returns the the number of object in a ObjectStorage instance
     * 
     * @return int $likesCount
     */
    public function getLikes(): int
    {
        return $this->votedUsers->count();
    }

    /**
     * Adds a like from a user to the idea
     *
     * @param FrontendUser The user to be added
     * @return void
     * @api
     */
    public function addVotedUsers(FrontendUser $frontendUser)
    {
       $this->votedUsers->attach($frontendUser);
    }

    /**
     * Returns the votedUsers
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUser> $votedUsers
     */
    public function getVotedUsers()
    {
        return $this->votedUsers;
    }
    
    /**
     * Sets the votedUsers
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FrontendUser> $votedUsers
     * @return void
     */
    public function setVotedUsers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $votedUsers)
    {
        $this->votedUsers = $votedUsers;
    }

    /**
     * Checks if the current user has liked the current idea
     * @return bool
     */
    public function isLikedByCurrentUser(): bool
    {
        $currentUser = GeneralUtility::makeInstance(ObjectManager::class)
            ->get(FrontendUserRepository::class)
            ->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
        if ($currentUser) {
            return $this->votedUsers->contains($currentUser);
        }

        return false;
    }

    /**
     * Returns the image
     * 
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image = null)
    {
        $this->image = $image;
    }

    /**
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param bool $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }
}