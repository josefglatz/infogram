<?php

namespace JosefGlatz\Infogram\Service;

use Infogram\InfogramRequest;
use Infogram\RequestSigningSession;
use JosefGlatz\Infogram\Domain\Model\Dto\ExtensionConfiguration;
use JosefGlatz\Infogram\Exception\ApiNoResponseException;
use JosefGlatz\Infogram\Exception\ApiNotOkException;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class ApiService
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $apiSecret;
    /**
     * @var string
     */
    private $apiUsername;

    /**
     * @var RequestSigningSession
     */
    private $apiSession;

    /**
     * ApiService constructor.
     *
     * @TODO Proxy settings usage: find way to support a proxy. infogram/infogram doesn't support a proxy actually.
     */
    public function __construct()
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->apiKey = $extensionConfiguration->getApiKey();
        $this->apiSecret = $extensionConfiguration->getApiSecret();
        $this->apiUsername = $extensionConfiguration->getUsername();
        $this->apiSession = new RequestSigningSession($this->apiKey, $this->apiSecret);
    }

    /**
     *  Return all available
     */
    public function getInfographics()
    {
        $request = new InfogramRequest(
            $this->apiSession,
            'GET',
            'users/' . $this->apiUsername  . '/infographics',
            null,
            null);
        $response = $request->execute();

        if (! $response) {
            throw new ApiNoResponseException("Cannot connect to the infogram server");
        }

        if (!$response->isOK()) {
            throw new ApiNotOkException("Could not execute infogram request");
        }

        return $response;
    }

    public function check_settings()
    {

    }

    public function getInfographic ($infographicId = '')
    {
        $request = new InfogramRequest(
            $this->apiSession,
            'GET',
            'infographics/' . $infographicId
        );
        $response = $request->execute();

        if (! $response) {
            throw new ApiNoResponseException("Cannot connect to the infogram server");
        }

        if (!$response->isOK()) {
            throw new ApiNotOkException("Could not execute infogram request");
        }

        return $response->getBody();
    }
}
