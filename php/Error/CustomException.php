<?php
namespace PayBear\Error;

use Exception;

class CustomException extends Exception
{
	public function __construct($error)
	{
		$error = "Error: ${error}";
		$error .= "If this problem persists, please contact support at https://www.paybear.io/contact.";
		parent::__construct($error);
	}
}