<?php
/**
 * Services_TwitPicTest 
 * 
 * PHP Version 5.1.0+
 * 
 * @uses      PHPUnit_Framework_TestCase
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */

/**
 * @uses PHPUnit_Framework_TestCase 
 */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @uses Services_TwitPic_Request_Mock 
 */
require_once 'Services/TwitPic/Request/Mock.php';

/**
 * @uses Services_TwitPic 
 */
require_once 'Services/TwitPic.php';

/**
 * All Tests for Services_TwitPic
 * 
 * @uses      PHPUnit_Framework_TestCase
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */
class Services_TwitPicTest extends PHPUnit_Framework_TestCase
{
    /**
     * Instance of Services_TwitPic
     * 
     * @var mixed
     * @access protected
     */
    protected $twit;

    /**
     * Canned success response
     * 
     * @var string
     * @access protected
     */
    protected $responseSuccess = '<?xml version="1.0" encoding="UTF-8"?>
<rsp status="ok">
 <statusid>1111</statusid>
 <userid>11111</userid>
 <mediaid>abc123</mediaid>
 <mediaurl>http://twitpic.com/abc123</mediaurl>
</rsp>';

    /**
     * Canned failure response
     * 
     * @var string
     * @access protected
     */
    protected $responseFailure = '<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="fail">
    <err code="1001" msg="Invalid twitter username or password" />
</rsp>';


    /**
     * Instantiates Services_TwitPic with the Mock requestor for each test
     * 
     * @access public
     * @return void
     */
    public function setUp()
    {
        $this->twit = new Services_TwitPic('foo', 'bar', 'Mock');
    }

    /**
     * Makes sure that the Services_TwitPic instance is of the right type
     * 
     * @access public
     * @return void
     */
    public function testConstruct()
    {
        $this->assertType('Services_TwitPic', $this->twit);
    }

    /**
     * Tests setting/getting options successfully
     * 
     * @access public
     * @return void
     */
    public function testOptionsSuccess()
    {
        $options = array('timeout' => 5, 'userAgent' => 'Test User Agent 1.0');
        $this->twit->setOptions($options);
        foreach ($options as $key => $val) {
            $this->assertSame($options[$key], $this->twit->getOption($key));
        }
    }

    /**
     * Tests failure of setting/getting options
     * 
     * @access public
     * @return void
     */
    public function testOptionsFailure()
    {
        $options = array('foo' => 5);
        $this->twit->setOptions($options);
        $this->assertSame($this->twit->getOption('foo'), null);
    }

    /**
     * Tests a successful upload()
     * 
     * @access public
     * @return void
     */
    public function testUploadSuccess()
    {
        Services_TwitPic_Request_Mock::$responseBody = $this->responseSuccess;
        Services_TwitPic_Request_Mock::$responseCode = 
            Services_TwitPic::HTTP_STATUS_OK;

        $xml = $this->twit->upload('image.jpg');
        $this->assertType('SimpleXMLElement', $xml);
        $this->assertSame((string)$xml->attributes()->status, 'ok');
    }

    /**
     * Tests an API error response for upload()
     * 
     * @access public
     * @return void
     */
    public function testUploadFailure()
    {
        Services_TwitPic_Request_Mock::$responseBody = $this->responseFailure;
        Services_TwitPic_Request_Mock::$responseCode = 
            Services_TwitPic::HTTP_STATUS_OK;
        try {
            $xml = $this->twit->upload('image.jpg');
        } catch (Services_TwitPic_Exception $e) {
        }
        $this->assertSame($e->getCode(), 1001);
    }

    /**
     * Tests a 500 response from the API web server
     * 
     * @access public
     * @return void
     */
    public function testUploadFailureExceptionCode500()
    {
        Services_TwitPic_Request_Mock::$responseBody = $this->responseSuccess;
        Services_TwitPic_Request_Mock::$responseCode = 500;
        try {
            $xml = $this->twit->upload('image.jpg');
        } catch (Services_TwitPic_Exception $e) {
        }
        $this->assertSame($e->getCode(), 500);
        $this->assertSame($e->getMessage(), $this->responseSuccess);
    }


    /**
     * Tests a successful uploadAndPost()
     * 
     * @access public
     * @return void
     */
    public function testUploadAndPostSuccess()
    {
        Services_TwitPic_Request_Mock::$responseBody = $this->responseSuccess;
        Services_TwitPic_Request_Mock::$responseCode = 
            Services_TwitPic::HTTP_STATUS_OK;

        $xml = $this->twit->uploadAndPost('image.jpg', 'test message');
        $this->assertType('SimpleXMLElement', $xml);
        $this->assertSame((string)$xml->attributes()->status, 'ok');
    }

    /**
     * Tests a failure response for uploadAndPost()
     * 
     * @access public
     * @return void
     */
    public function testUploadAndPostFailure()
    {
        Services_TwitPic_Request_Mock::$responseBody = $this->responseFailure;
        Services_TwitPic_Request_Mock::$responseCode = 200;
        try {
            $xml = $this->twit->uploadAndPost('image.jpg', 'test message');
        } catch (Services_TwitPic_Exception $e) {
        }
        $this->assertSame($e->getCode(), 1001);
    }

    /**
     * testUploadAndPostFailureExceptionCode500 
     * 
     * Test a 500 response code from the API server for uploadAndPost()
     * 
     * @access public
     * @return void
     */
    public function testUploadAndPostFailureExceptionCode500()
    {
        Services_TwitPic_Request_Mock::$responseBody = $this->responseSuccess;
        Services_TwitPic_Request_Mock::$responseCode = 
            Services_TwitPic::HTTP_STATUS_INTERNAL_ERROR;

        try {
            $xml = $this->twit->uploadAndPost('image.jpg', 'test message');
        } catch (Services_TwitPic_Exception $e) {
        }
        $this->assertSame($e->getCode(), 500);
        $this->assertSame($e->getMessage(), $this->responseSuccess);
    }

    /**
     * tearDown 
     * 
     * Unset the Services_TwitPic instance after each test
     * 
     * @access public
     * @return void
     */
    public function tearDown()
    {
        $this->twit = null;
    }    
}
