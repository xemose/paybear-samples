<?php
namespace PayBear;

use PayBear\PayBear;
use PayBear\Error;

class ApiRequest
{
	// @var string The PayBear API key used for requests.
	private $apiKey;

	// @var string The API base URL being used for requests.
	private $apiBase;

	// @var object Object for HTTP Client
	private static $httpClient;

    /**
     * API Request constructor.
     *
     * @param string|null $apiKey
     * @param string|null $apiBase
     */
	public function __construct($apikey = null, $apiBase = null)
	{
		$this->apiKey = $apiKey;
		$this->apiBase = (!$apiBase) ? PayBear::$apiBase : $apiBase;
	}

	
}