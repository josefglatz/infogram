<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'JosefGlatz.' . $_EXTKEY,
    'Show',
    'LLL:EXT:infogram/Resources/Private/Language/locallang.xlf:plugin.alternativeTitle'
);
