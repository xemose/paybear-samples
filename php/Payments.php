<?php
namespace PayBear;

use PayBear\PayBear;
use PayBear\ApiRequest;

/**
 * Class Payments
 * Payment class to create and track transactions
 *
 * @package PayBear
 */
class Payments extends ApiResource
{
	use ApiOperations\All;
	use ApiOperations\Create;

	public static function classUrl()
	{
		$callback = PayBear::$callback;
		$token = PayBear::$apiKey;
		return "%s/payment/${callback}?token=${token}";
	}

	public function createTransaction($currency)
	{

	}
}