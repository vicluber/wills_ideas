<?php

/**
 * Extension Manager/Repository config file for ext "wills_ideas".
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Wills Ideas',
    'description' => 'Adding data records for the ideas platform',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '10.2.0-10.4.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Wills\\WillsIdeas\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Victor Willhuber',
    'author_email' => 'victorwillhuber@gmail.com',
    'author_company' => 'Wills',
    'version' => '1.0.0',
];
