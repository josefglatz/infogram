<?php

namespace JosefGlatz\Infogram\Hooks\Backend;

use JosefGlatz\Infogram\Service\ApiService;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\FlexFormService;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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
                    $published = ' [ Not Published ]';
                }
                $lastEdit = ' [' . BackendUtility::datetime(date('U', strtotime($infographic->date_modified))) . ']';
                $title = sprintf('%s%s%s', $title, $published, $lastEdit);
                $config['items'][] = [$title, $id];
            }
        } catch (\Exception $e) {
            // do nothing
        }
    }
}
