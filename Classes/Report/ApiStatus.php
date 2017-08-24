<?php declare(strict_types=1);
namespace JosefGlatz\Infogram\Report;

use Doctrine\Common\Util\Debug;
use JosefGlatz\Infogram\Service\ApiService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Reports\Status;

/**
 * Provides an status report about whether the connection to tha API
 * server is possible with the extension.
 *
 */
class ApiStatus extends AbstractInfogramStatus
{

    /**
     * Show status about Infogr.am API
     *
     */
    public function getStatus()
    {
        $reports = [];
        $severity = Status::OK;
        $value = 'OK';
        $report = '';

        $api = GeneralUtility::makeInstance(ApiService::class);
        $check = $api->checkSettings();

        if ($check === false) {
            $severity = Status::ERROR;
            $value = 'API response in not ok';
            $variables = [
                'response' => $check
            ];
            $report = $this->getRenderedReport('ApiStatus.html', $variables);
        }

        $reports[] = GeneralUtility::makeInstance(Status::class, 'Infogram.com API Connection', $value, $report, $severity);

        return $reports;
    }
}
