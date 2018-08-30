<?php
namespace PayBear;

use PayBear\HttpClient\ClientInterface;

/**
 * Class Payments
 * Payment class to create and track transactions
 *
 * @package PayBear
 */
class Payments
{
	public function createTransaction($currency)
	{
		$url = sprintf('https://api.paybear.io/v2/eth/payment/%s?token=%s', urlencode($callbackUrl), $apiSecret);
	}
}