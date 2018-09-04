<?php
namespace PayBear;

use PayBear\HttpClient;
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
		$this->apiBase = (!$apiBase) ? PayBear::$absUrl : $apiBase;
	}

	 /**
     *	API Raw Request Function
     *
     * @param string $method
     * @param string $url
     * @param string|null @headers
     * @param string|null @params
     * @return array An array whose first element is an API 
     * response and second element is the API key used to make * the request.
     */
	public function request($method, $url, $headers = null, $params=null)
	{
		$params = $params ?: [];
		$headers = $headers ?: [];
		list($rbody, $rcode, $rheaders, $apiKey) = self::_requestRaw($method, $url, $params, $headers);
		$json = self::_interceptResponse($rbody, $rcode, $rheaders);
		$resp = new APIResponse($rbody, $rcode, $rheaders, $json);
		return [$resp, $apiKey];
	}

	private static function _defaultHeaders($headers = null)
	{
		$userAgent = PayBear::$userAgent;
		$defaultHeaders = [
			'Content-Type' => 'application/json',
			'User-Agent' => $userAgent
		];
		return $defaultHeaders;
	}

	 /**
     *	API Raw Request Function
     *
     * @param string $method
     * @param string $url
     * @param string|null @headers
     * @param string|null @params
     * @throws Error\CustomException
     */
	private function _requestRaw($method, $url, $headers = null, $params = null)
	{
		if ($this->apiKey) {
			$absUrl = self::apiBase.'/'.$url;
			$defaultHeaders = self::_defaultHeaders($headers);
			if ($params) {
				// Future compabitlity for custom paramteres for requests.
			}
			list($rbody, $rcode, $rheaders) = self::$httpClient($method, $absUrl);
			return [$rbody, $rcode, $rheaders, $this->apiKey];
		} else {
			throw new Error\CustomException('No API key provided.');
		}
	}

	 /**
     *	Initalizes HTTP clent for requests.
     *
     * @return HttpClient\ClientInterface
     */
	private function httpClient()
	{
		if (!self::$httpClient) {
			self::$httpClient = HttpClient\CurlClient::instance();
		}
		return self::$httpClient;
	}
}