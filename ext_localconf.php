<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:' . $extKey . '/Configuration/TSConfig/ContentElementWizard.typoscript">');

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'JosefGlatz.' . $extKey,
            'Show',
            [
                'Infogram' => 'show',
            ],
            [
                'Infogram' => 'show',
            ]
        );

        // Only backend relevant stuff
        if (TYPO3_MODE === 'BE') {
            // Register icons provided by this extension
            /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
            $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
            $iconRegistry->registerIcon(
                'ext-infogram-wizard-icon',
                \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                ['source' => 'EXT:' . $extKey . '/Resources/Public/Icons/Extension.svg']
            );

            // Page module hook
//            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$extKey . '_show'][$_EXTKEY] =
//                \JosefGlatz\Infogram\Hooks\Backend\PageLayoutViewHook::class . '->getExtensionSummary';
        }
    },
    $_EXTKEY
);
