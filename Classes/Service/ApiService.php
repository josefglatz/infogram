<?php

namespace JosefGlatz\Infogram\Service;

use Infogram\InfogramRequest;
use Infogram\InfogramResponse;
use Infogram\RequestSigningSession;
use JosefGlatz\Infogram\Domain\Model\Dto\ExtensionConfiguration;
use JosefGlatz\Infogram\Exception\ApiNoResponseException;
use JosefGlatz\Infogram\Exception\ApiNotOkException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Infogram API Service
 *
 * Class ApiService
 * @package JosefGlatz\Infogram\Service
 */
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
     * Retrieve available infographics
     *
     * @throws \JosefGlatz\Infogram\Exception\ApiNoResponseException
     * @throws \JosefGlatz\Infogram\Exception\ApiNotOkException
     */
    public function getInfographics(): InfogramResponse
    {
        $request = new InfogramRequest(
            $this->apiSession,
            'GET',
            'users/' . $this->apiUsername . '/infographics');
        $response = $request->execute();

        $this->checkForResponse(
            $response,
            'Can\t connect to the infogram server while trying to fetch available infographics'
        );
        $this->checkForValidRequest($response);

        return $response;
    }

    public function checkSettings()
    {

    }

    /**
     * Retrieve specific infographic
     *
     * @param $infographicId
     *
     * @return \stdClass
     * @throws \JosefGlatz\Infogram\Exception\ApiNotOkException
     * @throws \JosefGlatz\Infogram\Exception\ApiNoResponseException
     */
    public function getInfographic($infographicId): \stdClass
    {
        $request = new InfogramRequest(
            $this->apiSession,
            'GET',
            'infographics/' . $infographicId
        );
        $response = $request->execute();

        $this->checkForResponse(
            $response,
            'Can\t connect to the infogram server while trying to fetch a specific infographic'
        );
        $this->checkForValidRequest($response);

        return $response->getBody();
    }

    /**
     * Check if the infogr.am API server can be accessed.
     * - Infogram\InfogramResponse is returned, if the API server can be accessed
     * - Null is returned, if the API server can't be accessed
     *
     * @param mixed $response
     * @param string $message
     *
     * @throws ApiNoResponseException
     */
    protected function checkForResponse(InfogramResponse $response, string $message = 'Can\'t connect to the infogram server')
    {
        if (!$response) {
            throw new ApiNoResponseException($message);
        }
    }

    /**
     * Check for successful API request
     * - On error, getBody method returns string which contains the error message
     *
     * @param mixed $response
     * @param string $message
     *
     * @throws ApiNotOkException
     */
    protected function checkForValidRequest(InfogramResponse $response, string $message = 'API couldn\'t execute request')
    {
        if (!$response->isOK()) {
            throw new ApiNotOkException($message . ': ' . $response->getBody());
        }
    }
}
