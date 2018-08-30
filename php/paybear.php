<?php

namespace PayBear;

/**
 * Class PayBear
 * Main class to set library options
 *
 * @package PayBear
 */
class PayBear
{
	// @var string The PayBear API key used for requests.
	public static $apiKey;

	// @var string The base URL for the PayBear API.
	public static $apiBase = 'https://api.paybear.io';

	// @var string The API version used.
	public static $apiVersion = 'v2';

	// @var int Maximum number of request retries
	public static $maxNetworkRetries = 2;

    // @var float Maximum delay between retries, in seconds
	private static $maxNetworkRetryDelay = 2.0;

    // @var float Initial delay between retries, in seconds
	private static $initialNetworkRetryDelay = 0.5;

    /**
     * @return string The PayBear API key used for requests.
     */
    public static function getAPIKey()
    {
    	return self::$apiKey;
    }

    /**
     * Sets the PayBear API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setAPIKey($apiKey)
    {
    	self::$apiKey = $apiKey;
    }

    /**
     * @return string The API version used for requests
     */
    public static function getApiVersion()
    {
    	return self::$apiVersion;
    }

    /**
     * Sets the API version used for requests.
     *
     * @param string $apiVersion
     */
    public static function setApiVersion($apiVersion)
    {
    	self::$apiVersion = $apiVersion;
    }

    /**
     * @return int Maximum number of request retries
     */
    public static function getMaxNetworkRetries()
    {
    	return self::$maxNetworkRetries;
    }

    /**
     * @param int $maxNetworkRetries Maximum number of request retries
     */
    public static function setMaxNetworkRetries($maxNetworkRetries)
    {
    	self::$maxNetworkRetries = $maxNetworkRetries;
    }

    /**
     * @return float Maximum delay between retries, in seconds
     */
    public static function getMaxNetworkRetryDelay()
    {
    	return self::$maxNetworkRetryDelay;
    }

    /**
     * @return float Initial delay between retries, in seconds
     */
    public static function getInitialNetworkRetryDelay()
    {
    	return self::$initialNetworkRetryDelay;
    }
}