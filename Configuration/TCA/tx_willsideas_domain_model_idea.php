<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea',
        'label' => 'title',
        'iconfile' => 'EXT:wills_ideas/Resources/Public/Icons/Extension.svg',
        'enablecolumns' => [ 
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
    ],
    'columns' => [
        'title' => [
            'label' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.title',
            'config' => [
                'type' => 'input',
                'size' => '20',
                'eval' => 'trim',
            ],
        ],
        'description' => [
            'label' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.description',
            'config' => [
                'type' => 'text',
                'eval' => 'trim',
            ],
        ],
        'category' => [
            'label' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'treeConfig' => [
                    'parentField' => 'parent',
                    'rootUid' => $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['wills_ideas']['IdeasParentCategory'],
                    'appearance' => [
                        'showHeader' => false,
                        'expandAll' => true,
                    ],
                ],
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND (sys_category.sys_language_uid = 0 OR sys_category.l10n_parent = 0) ORDER BY sys_category.sorting',
                'size' => 10,
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ],
         'status' => [
            'label' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.status',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.submitted', 1],
                    ['LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.progress', 2],
                    ['LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.finished', 3],
                ],
            ],
        ],
        'user' => [
            'label' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_be.xlf:ideas_idea.user',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'fe_users',
                'renderType' => 'selectSingle',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'voted_users' => [
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users',
                'MM' => 'tx_willsideas_domain_model_idea_user_mm',
                'enableMultiSelectFilterTextfield' => true,
                'internal_type' => 'db',
                'MM_opposite_field' => 'voted_users',
            ]
        ],
        'image' => [
            'label' => 'image',
            'config' =>
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                    'image',
                    [
                        'appearance' => [
                            'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                        ],
                        'foreign_types' => [
                            '0' => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                            ]
                        ],
                        'maxitems' => 1
                    ],
                    $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                ),
    
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:wills_ideas/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'title, description, category, status, user, voted_users, image, hidden'],
    ],
];
