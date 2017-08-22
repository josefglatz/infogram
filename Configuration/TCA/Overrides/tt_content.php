<?php
defined('TYPO3_MODE') or die();

/***************
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'infogram',
    'Show',
    'LLL:EXT:infogram/Resources/Private/Language/locallang.xlf:plugin.alternativeTitle'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['infogram_show'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['infogram_show'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('infogram_show',
    'FILE:EXT:infogram/Configuration/FlexForms/flexform_infogram.xml');
