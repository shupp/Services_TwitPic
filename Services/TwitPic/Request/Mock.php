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

require_once 'Services/TwitPic/Request/HTTPRequest.php';
require_once 'Services/TwitPic/Exception.php';

/**
 * Services_TwitPic_Request_Mock 
 * 
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
     * responseBody
     * 
     * Canned response body for testing
     * 
     * @var string
     * @access public
     */
    static public $responseBody;

     /**
     * responseCode
     * 
     * Canned response code for testing
     * 
     * @var string
     * @access public
     */
    static public $responseCode;

    /**
     * sendRequest 
     * 
     * Don't actually send anything
     * 
     * @access public
     * @return void
     */
    public function sendRequest()
    {
    }

    /**
     * getResponseCode 
     * 
     * Return the canned response code
     * 
     * @access public
     * @return void
     */
    public function getResponseCode()
    {
        return self::$responseCode;
    }

    /**
     * getResponseBody 
     * 
     * Return the canned response body
     * 
     * @access public
     * @return void
     */
    public function getResponseBody()
    {
        return self::$responseBody;
    }
}
