<?php
namespace PayBear;

use PayBear\PayBear;
use PayBear\Error\CustomException;

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

	public function request($method, $url, $params = null, $headers=null)
	{
		$params = $params ?: [];
		$headers = $headers ?: [];
		list($rbody, $rcode, $rheaders, $apiKey) = self::_requestRaw($method, $url, $params, $headers);
		$json = self::_interceptResponse($rbody, $rcode, $rheaders);
		$resp = new APIResponse($rbody, $rcode, $rheaders, $json);
		return [$resp, $apiKey];
	}

	private function _requestRaw($method, $url, $params, $headers)
	{
		if ($this->apiKey) {

		} else {
			throw new CustomException('No API key provided.')
		}
	}
}