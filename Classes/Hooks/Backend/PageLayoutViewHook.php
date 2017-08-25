<?php

namespace JosefGlatz\Infogram\Hooks\Backend;

use JosefGlatz\Infogram\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Lang\LanguageService;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

class PageLayoutViewHook
{
    /**
     * Extension key
     *
     * @var string
     */
    const KEY = 'infogram';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE = 'tt_content';

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

    /** @var ApiService */
    protected $api;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    public function __construct()
    {
        // instantiate infogram API
        $this->api = GeneralUtility::makeInstance(ApiService::class);
    }

    /**
     * @TODO check if infographic uid is valid; if not show info that infographic couldn't be fetched (maybe some api problems?)
     *
     * @param array $params
     *
     * @return string
     */
    public function getExtensionSummary(array $params = []): string
    {
        $header = '<strong class="text-muted">' . htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'plugin.title')) . '</strong><br>';
        $result = '';

        if ($params['row']['list_type'] == self::KEY . '_show') {

            $this->flexformData = GeneralUtility::xml2array($params['row']['pi_flexform']);

            // if flexform data is found
            $infographicId = $this->getFieldFromFlexform('settings.infographicId');
            if (!empty($infographicId)) {
                $infographic = $this->api->getInfographic(trim($infographicId));
                $view = $this->getFluidTemplateObject('PageLayoutView.html');
                $view->assignMultiple([
                    'infographic' => $infographic,
                    'row' => $params['row'],
                    'editLink' => $this->getEditLink($params['row'], $params['row']['pid']),
                    'lastModified' => $this->lastModified($infographic->date_modified),
                ]);
                $result .= $view->render();
            } else {
                $header .= $this->generateCallout($this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.infographic.not_configured'), $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.infographic.not_configured.description'));
            }
        }

        return $header . $result;
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
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Get field value from flexform configuration,
     * including checks if flexform configuration is available
     *
     * @param string $key name of the key
     * @param string $sheet name of the sheet (default = 'sDEF')
     *
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

    /**
     * Render an alert box
     *
     * @param string $text
     * @return string
     */
    protected function generateCallout(string $header, string $text): string
    {
        return '<div class="alert alert-warning" role="alert">
            <strong>' . htmlspecialchars($header) . '</strong> ' . htmlspecialchars($text) . '
        </div>';
    }

    protected function getEditLink(array $row): string
    {
        $url = '';
        if ($this->getBackendUser()->recordEditAccessInternals('tt_content', $row)) {
            $urlParameters = [
                'edit' => [
                    'tt_content' => [
                        $row['uid'] => 'edit'
                    ]
                ],
                'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI') . '#element-tt_content-' . $row['uid'],
            ];
            $url = BackendUtility::getModuleUrl('record_edit', $urlParameters);
        }
        return $url;
    }

    /**
     * Returns a new standalone view, shorthand function
     *
     * @param string $filename Which templateFile should be used.
     * @return StandaloneView
     */
    protected function getFluidTemplateObject(string $filename): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setLayoutRootPaths(['EXT:infogram/Resources/Private/Layouts/Backend']);
        $view->setPartialRootPaths([
            'EXT:infogram/Resources/Private/Partials/Backend/PageLayout'
        ]);
        $view->setTemplateRootPaths(['EXT:infogram/Resources/Private/Templates/Backend/PageLayout']);

        $view->setTemplate($filename);

        $view->getRequest()->setControllerExtensionName('Infogram');
        return $view;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Get human readable modified date with/-out time based on
     * the last modified timestamp
     *
     * @param string $time Time string compatible to strtotime()
     *
     * @return string human readable datetime based on TYPO3 backend configuration
     */
    protected function lastModified(string $time): string
    {
        $dateModified = date('U', strtotime($time));
        if ($this->getModifiedAge($dateModified) > 7) {

            return BackendUtility::date($dateModified);
        }

        return BackendUtility::datetime($dateModified);
    }

    /**
     * Get age in days
     *
     * @param $dateModified
     *
     * @return int amount of days
     */
    protected function getModifiedAge($dateModified): int
    {
        $delta_t = -($dateModified - $GLOBALS['EXEC_TIME']);

        /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
        return ceil($delta_t / (3600 * 24));
    }
}
