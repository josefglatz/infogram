<?php

namespace JosefGlatz\Infogram\Hooks\Backend;

use JosefGlatz\Infogram\Service\ApiService;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lang\LanguageService;

class PageLayoutViewHook
{
    /**
     * Extension key
     *
     * @var string
     */
    const KEY = 'infogram';

    /**
     * Path to the locallang file
     *
     * @var string
     */
    const LLPATH = 'LLL:EXT:infogram/Resources/Private/Language/locallang.xlf:';

    /**
     * Table information
     *
     * @var array
     */
    protected $tableData = [];

    /**
     * @var array
     */
    protected $flexformData = [];

    /** @var  DatabaseConnection */
    protected $databaseConnection;

    /** @var ApiService */
    protected $api;

    public function __construct()
    {
        /** @var DatabaseConnection databaseConnection */
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
        $this->api = GeneralUtility::makeInstance(ApiService::class);
    }

    public function getExtensionSummary(array $params = []): string
    {
        $this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);

        $result = '<strong>' . htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'plugin.title')) . '</strong><br>';

        $this->getListInformation();

        $result .= $this->renderSettingsAsTable();
        return $result;
    }

    protected function getListInformation() {

    }

    /**
     * @param string $string
     * @param bool $hsc
     *
     * @return string
     */
    protected function getLabel($string, $hsc = true): string
    {
        $label = $this->getLanguageService()->sL(self::LLPATH . $string);
        if ($hsc) {
            $label = htmlspecialchars($label);
        }
        return $label;
    }

    /**
     * Return language service instance
     *
     * @return LanguageService
     */
    public function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Render the settings as table for Web>Page module
     * System settings are displayed in mono font
     *
     * @return string
     */
    protected function renderSettingsAsTable(): string
    {
        if (count($this->tableData) === 0) {
            return '';
        }

        $content = '';
        foreach ($this->tableData as $line) {
            $content .= ($line[0] ? ('<strong>' . $line[0] . '</strong>' . ' ') : '') . $line[1] . '<br />';
        }

        return '<pre style="white-space:normal">' . $content . '</pre>';
    }

    /**
     * Get field value from flexform configuration,
     * including checks if flexform configuration is available
     *
     * @param string $key name of the key
     * @param string $sheet name of the sheet
     * @return string|NULL if nothing found, value if found
     */
    protected function getFieldFromFlexform($key, $sheet = 'sDEF')
    {
        $flexform = $this->flexformData;
        if (isset($flexform['data'])) {
            $flexform = $flexform['data'];
            if (is_array($flexform) && is_array($flexform[$sheet]) && is_array($flexform[$sheet]['lDEF'])
                && is_array($flexform[$sheet]['lDEF'][$key]) && isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
            ) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }

        return null;
    }
}
