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

    protected function lastModified(string $time): string
    {
        return BackendUtility::datetime(date('U', strtotime($time)));
    }
}
