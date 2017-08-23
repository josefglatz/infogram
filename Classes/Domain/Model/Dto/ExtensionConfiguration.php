<?php

namespace JosefGlatz\Infogram\Domain\Model\Dto;

use JosefGlatz\Infogram\Exception\ApiKeyMissingException;
use JosefGlatz\Infogram\Exception\ApiSecretMissingException;
use JosefGlatz\Infogram\Exception\ProxyPortWrongTypeException;
use JosefGlatz\Infogram\Exception\UsernameMissingException;

class ExtensionConfiguration
{

    /** @var string */
    protected $apiKey;

    /** @var string */
    protected $apiSecret;

    /** @var string */
    protected $username;

    /** @var string */
    protected $proxy = '';

    /** @var string */
    protected $proxyPort = '';

    public function __construct()
    {
        /** @noinspection UnserializeExploitsInspection */
        $settings = (array)unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['infogram']);
        foreach ($settings as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     * @throws \JosefGlatz\Infogram\Exception\ApiKeyMissingException
     */
    public function getApiKey(): string
    {
        if (empty($this->apiKey)) {
            throw new ApiKeyMissingException('infogr.am API key is missing');
        }

        return $this->apiKey;
    }

    /**
     * @return string
     * @throws \JosefGlatz\Infogram\Exception\ApiSecretMissingException
     */
    public function getApiSecret(): string
    {
        if (empty($this->apiSecret)) {
            throw new ApiSecretMissingException('infogr.am API secret is missing');
        }

        return $this->apiSecret;
    }

    /**
     * @return string
     * @throws \JosefGlatz\Infogram\Exception\UsernameMissingException
     */
    public function getUsername(): string
    {
        if (empty($this->username)) {
            throw new UsernameMissingException('infogr.am username is missing');
        }
        return $this->username;
    }

    /**
     * @return string
     */
    public function getProxy(): string
    {
        return $this->proxy;
    }

    /**
     * @return string
     * @throws \JosefGlatz\Infogram\Exception\ProxyPortWrongTypeException
     */
    public function getProxyPort(): string
    {
        if (!empty($this->proxyPort) && !is_int($this->getProxyPort())) {
            throw new ProxyPortWrongTypeException('infogr.am proxyPort must be an integer value');
        }
        return (string)$this->proxyPort;
    }
}
