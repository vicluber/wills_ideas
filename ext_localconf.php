<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Wills.WillsIdeas',
    'IdeasList',
    [ \Wills\WillsIdeas\Controller\IdeasController::class => 'list, new, create, like, byCategory' ],
    [ \Wills\WillsIdeas\Controller\IdeasController::class => 'list, new, create, like, byCategory' ]
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Wills.WillsIdeas',
    'IdeasNew',
    [
        \Wills\WillsIdeas\Controller\IdeasController::class => 'new, create',
    ],
    [
        \Wills\WillsIdeas\Controller\IdeasController::class => 'new, create',
    ]
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Wills\\WillsIdeas\\Property\\TypeConverter\\UploadedFileReferenceConverter');
defined('TYPO3') or die();
