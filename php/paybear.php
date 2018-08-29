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
}