<?php

namespace JosefGlatz\Infogram\Hooks\Backend;

use JosefGlatz\Infogram\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Custom flexform fields support
 *
 * Class ItemsProcFunc
 * @package JosefGlatz\Infogram\Hooks\Backend
 */
class ItemsProcFunc
{
    /** @var ApiService */
    protected $api;

    public function __construct()
    {
        $this->api = GeneralUtility::makeInstance(ApiService::class);
    }

    /**
     * Retrieve available infographics list for flexform
     *
     * @param array $config
     */
    public function getList(array &$config)
    {
        try {
            $infographics = $this->api->getInfographics()->getBody();
            foreach ($infographics as $infographic) {
                $id = $infographic->id;
                $title = $infographic->title;
                $published = '';
                if (!$infographic->published) {
                    $published = ' (‼ NOT PUBLISHED)';
                }
                $lastEdit = ' [' . $this->lastModified($infographic->date_modified) . ']';
                $title = sprintf('📊 %s%s%s', $title, $published, $lastEdit);
                // Push infographic as select item
                $config['items'][] = [$title, $id];
            }
        } catch (\Exception $e) {
            // do nothing
        }
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
