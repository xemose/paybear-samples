<?php

namespace PayBear\HttpClient;

use PayBear\PayBear;
use PayBear\Error;
use PayBear\Util\RandomGenerator;

/**
 * Class Base
 * HTTP client class to make requests to API
 *
 * @package PayBear
 */
class MainClient implements ClientInterface
{
	// @var object Instance for HTTP Client
	private static $instance;

	// @var array Array of stable retry errors
	private static $stable;

	// @var string Timeout for curl requests
	private $timeout;

	// @var string Connection timeout for curl requests
	private $connectTimeout;

    /**
     * Initializes variable instance
     */
    public static function instance()
    {
    	if (!self::$instance) {
    		self::$instance = new self();
    	}
    	return self::$instance;
    }

    /**
     * Set up curl options for future requests
     * Calls initCurl function to setup default options
     *
     * @param string|null $options
     */
    public function __construct($options = null)
    {
    	self::setTimeouts($options);
    	self::$stable = [
    		'errno' => [
    			'CURLE_OPERATION_TIMEOUTED',
    			'CURLE_COULDNT_CONNECT'
    		],
    		'rcode' =>
    		[
    			'409'
    		]
    	];
    }

    // Default Timeout Values

    const DEFAULT_TIMEOUT = 60;
    const DEFAULT_CONNECT_TIMEOUT = 30;

    /**
     * Set up timeout for curl requests
     * Future ability to set custom timeouts
     *
     * @param string|null $options
     */
    public function setTimeouts($options = null)
    {
    	$this->timeout = self::DEFAULT_TIMEOUT;
    	$this->connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;
    }

    /**
     * @return string Timeout for curl requests.
     */
    public function getTimeout()
    {
    	return $this->timeout;
    }

    /**
     * @return string Connection timeout for curl requests.
     */
    public function getConnectTimeout()
    {
    	return $this->connectTimeout;
    }

   /**
     * Set up rqeuest for execution
     *
     * @param string $method The HTTP method being used.
     * @param string $url The URL being used.
     * @param array|null $params The paramters being used.
     * @param string|null $hasFile The file being used.
     */
    public function request($method, $url, $params = null, $hasFile = null)
    {
    	$method = strtolower($method);
    	$opts = [];

    	/*
    	* At the time of writing this library, PayBear's API * only accepted GET requests.
    	* So all other methods except GET has been disabled. 
    	* You can add or edit the cases below to add more 
    	* support for other HTTP methods in the future.
    	*/
    	switch ($method) {
    		case 'get':
    		if ($hasfile) {
    			throw new Exception('Error: Cannot make GET request with file parameter');
    		} else {
    			$opts['CURL_HTTPGET'] = 1;
    		}
    		break;
    		case 'post':
    		case 'delete':
    		throw new Exception('Error: HTTP Method not supported');
    		break;
    		default:
    		throw new Exception('Error: HTTP Method not supported');
    	}

    	/*
    	* Set up basic curl options.
    	* You can add more options in the future here.
    	*/
    	$opts['CURLOPT_URL'] = $url;
    	$opts['CURLOPT_RETURNTRANSFER'] = true;
    	$opts['CURLOPT_TIMEOUT'] = $this->timeout;
    	$opts['CURLOPT_CONNECTTIMEOUT'] = $this->connectTimeout;
    	$opts[CURLOPT_SSL_VERIFYPEER] = true;

    	/*
    	* Execute compiled request through function 
    	* executeRequest
		*/
    	$request = self::executeRequest($opts, $url);

    	return $request;
    }

    private function executeRequest($opts, $url)
    {
    	$numRetries = 0;
    	$rcode = null;
    	$errno = null;
    	$message = null;

    	while (true) {
    		$curl = curl_init();
    		curl_setopt_array($curl, $opts);
    		$response = curl_exec($curl);

    		if (curl_errno($curl)) {
    			$errno = curl_errno($curl);
    			$message = curl_error($curl);
    		}

    		$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    		curl_close($curl);

    		/*
    		* Check if client should retry request
    		*/

    		/*
    		* Report error to user if all requests failed
    		*/
    		$this->handleCurlError($url, $errno, $message, $numRetries);
    	}
    }

    /**
     * Handles any errors from curl requests
     *
 	 * @param string $url
     * @param int $errno
     * @param string $message
     * @param int $numRetries
     * @throws Exception
     */
    private function handleCurlError($url, $errno, $message, $numRetries)
    {
    	switch ($errno) {
    		case CURLE_COULDNT_CONNECT:
    		case CURLE_COULDNT_RESOLVE_HOST:
    		case CURLE_OPERATION_TIMEOUTED:
    		$msg = "Could not connect to ${url}. Please check your internet connection and try again.";
    		break;
    		case CURLE_SSL_CACERT:
    		case CURLE_SSL_PEER_CERTIFICATE:
    		$msg = "Could not verify PayBear's SSL certificate. Your network might be intercepting your requests. Try going to ${url} in your browser.";
    		break;
    		default:
    		$msg = "An unexcepted error occured while trying to communicate with PayBear's API";
    	}
    	$msg .= "If this problem persists, please contact support at https://www.paybear.io/contact.";
    	$msg .= "\n\nRequest Information:";
    	$msg .= "\nErrno: ${errno}";
    	$msg .= "\nError Message: ${message}";
    	$msg .= "\nNumber of retries: ${numRetries}";
    	throw new Exception("Error: ${msg}");
    }

    /**
     * Checks if request should be retried.
     *
     * @param int $errno
     * @param int $rcode
     * @param int $numRetries
     * @return bool
     */
    private function retryCalculate($errno, $rcode, $numRetries)
    {
    	if ($numRetries <= PayBear::getMaxNetworkRetries() && in_array($errno, self::$stable['Errno']) && in_array($rcode, self::$stable['rcode'])) {
    		return true;
    	} else {
    		return false;
    	}
    }

     /**
     * Determines and executes request sleep time
     *
     * @param int $numRetries
     * @return bool
     */
     private function sleepTime($numRetries)
     {
     	$sleepSeconds = min(PayBear::getInitialNetworkRetryDelay() * 1.0 * pow(2, $numRetries - 1), PayBear::getMaxNetworkRetryDelay());
     	$sleepSeconds *= 0.5 * (1 + randomGenerator::randFloat());
     	$sleepSeconds = max(PayBear::getInitialNetworkRetryDelay(), $sleepSeconds);
     	return $sleepSeconds;
     }
 }