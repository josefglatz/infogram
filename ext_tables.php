<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'JosefGlatz.' . $extKey,
            'Show',
            'LLL:EXT:infogram/Resources/Private/Language/locallang.xlf:plugin.alternativeTitle'
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_reports']['status']['providers']['Infogram Extension'] = [
            \JosefGlatz\Infogram\Report\ApiStatus::class
        ];
    },
    $_EXTKEY
);
