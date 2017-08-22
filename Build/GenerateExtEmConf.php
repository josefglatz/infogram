#!/usr/bin/php
<?php
$composerJson = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true);
$extEmConfContent = [
    'title' => 'infogram.com Content Element',
    'description' => $composerJson['description'],
    'category' => 'plugin',
    'author' => $composerJson['authors'][0]['name'],
    'author_email' => $composerJson['authors'][0]['email'],
    'author_company' => $composerJson['authors'][0]['homepage'],
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => shell_exec('git describe --tags $(git rev-list --tags --max-count=1)'),
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.4-8.7.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    $composerJson['autoload'],
];
$extEmConfContent = '<?php
$EM_CONF[$_EXTKEY] = ' . var_export($extEmConfContent, true) . ';
';
file_put_contents(__DIR__ . '/../ext_emconf.php', $extEmConfContent);
