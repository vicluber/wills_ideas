<?php
namespace Wills\WillsIdeas\Domain\Repository;

use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository as ExtbaseFrontendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FrontendUserRepository extends ExtbaseFrontendUserRepository
{
    /**
     * Disable respecting of a storage pid within queries globally.
     */
    public function initializeObject()
    {
        $defaultQuerySettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $defaultQuerySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }
}