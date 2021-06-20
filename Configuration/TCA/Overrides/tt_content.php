<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Wills.WillsIdeas',
    'IdeasList',
    'Ideas List view',
    'EXT:wills_ideas/Resources/Public/Icons/Extension.svg'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['willsideas_ideaslist'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['willsideas_ideaslist'] = 'recursive,select_key,pages';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    // plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
    'willsideas_ideaslist',
    // Flexform configuration schema file
    'FILE:EXT:wills_ideas/Configuration/FlexForms/IdeasList.xml'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Wills.WillsIdeas',
    'IdeasNew',
    'Ideas New view',
    'EXT:wills_ideas/Resources/Public/Icons/Extension.svg'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['willsideas_ideasnew'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['willsideas_ideasnew'] = 'recursive';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    // plugin signature: <extension key without underscores> '_' <plugin name in lowercase>
    'willsideas_ideasnew',
    // Flexform configuration schema file
    'FILE:EXT:wills_ideas/Configuration/FlexForms/IdeasNew.xml'
);