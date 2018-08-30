<?php
namespace PayBear\HttpClient;

interface ClientInterface
{
    /**
     * @param string $method The HTTP method being used for 
     * requests.
     * @param string $url The URL being used for requests.
     *
     * @throws \Exception
     * @return array An array with request details
     *
     */
    public function request($method, $url);
}