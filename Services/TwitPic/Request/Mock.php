<?php
/**
 * Services_TwitPic_Request_Mock 
 * 
 * PHP Version 5.1.0+
 * 
 * @uses      Services_TwitPic_Request_HTTPRequest
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */

/**
 * @uses Services_TwitPic_Request_HTTPRequest 
 */
require_once 'Services/TwitPic/Request/HTTPRequest.php';

/**
 * @uses Servics_TwitPic_Exception
 */
require_once 'Services/TwitPic/Exception.php';

/**
 * Class for mocking response body, code, and supressing sending data.
 * 
 * @uses      Services_TwitPic_Request_HTTPRequest
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */
class Services_TwitPic_Request_Mock extends Services_TwitPic_Request_HTTPRequest
{
    /**
     * Canned response body for testing
     * 
     * @var string
     */
    static public $responseBody;

     /**
     * Canned response code for testing
     * 
     * @var string
     */
    static public $responseCode;

    /**
     * Doesn't actually send anything
     * 
     * @return void
     */
    public function sendRequest()
    {
    }

    /**
     * Returns the canned response code
     * 
     * @return void
     */
    public function getResponseCode()
    {
        return self::$responseCode;
    }

    /**
     * Returns the canned response body
     * 
     * @return void
     */
    public function getResponseBody()
    {
        return self::$responseBody;
    }
}
