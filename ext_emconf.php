<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'infogr.am',
    'description' => 'Display infogram content element',
    'category' => 'plugin',
    'author' => 'Josef Glatz',
    'author_email' => 'jousch@gmail.com',
    'author_company' => 'http://jousch.com',
    'state' => 'stable',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'version' => '0.1.0',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '8.7.1-8.7.99',
                ],
            'conflicts' =>
                [
                ],
            'suggests' =>
                [
                ],
        ],
    'autoload' =>
        [
            'psr-4' =>
                [
                    'JosefGlatz\\Infogram\\' => 'Classes',
                ],
        ],
];
