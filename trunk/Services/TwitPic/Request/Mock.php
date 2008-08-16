<?php

require_once 'Services/TwitPic/Request/HTTPRequest.php';
require_once 'Services/TwitPic/Exception.php';

class Services_TwitPic_Request_Mock extends Services_TwitPic_Request_HTTPRequest
{
    static public $requestResponse;
    static public $responseCode;
    static public $responseBody;
    public function sendRequest()
    {
    }
    public function getResponseCode()
    {
        return self::$responseCode;
    }
    public function getResponseBody()
    {
        return self::$responseBody;
    }
}
