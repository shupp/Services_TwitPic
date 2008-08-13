<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/TwitPic.php';
require_once 'TwitPicTest/Mock.php';

class Services_TwitPicTest extends PHPUnit_Framework_TestCase
{
/*
    public function __construct($username, $password)
    public function setOptions(array $options)
    public function upload($file)
    public function uploadAndPost($file, $message = null)
    protected function sendRequest($endPoint, array $params)
*/

    public function testConstructSuccess()
    {
        $userPassArray = array('foo', 'bar');
        $twit = new Services_TwitPicTest_Mock('foo', 'bar');
        $this->assertType('Services_TwitPic', $twit);
        $this->assertSame($userPassArray, $twit->getUserPass());
    }

    public function testSetOptionsSuccess()
    {
        $options = array('timeout' => 5, 'userAgent' => 'Test User Agent 1.0');
        $twit = new Services_TwitPicTest_Mock('foo', 'bar');
        $twit->setOptions($options);
        $this->assertSame($options, $twit->getOptions());
    }
}
