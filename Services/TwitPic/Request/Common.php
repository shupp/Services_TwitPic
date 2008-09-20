<?php
/**
 * Services_TwitPic_Request_Common 
 * 
 * PHP Version 5.1.0+
 * 
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */

/**
 * @uses Services_TwitPic
 */
require_once 'Services/TwitPic.php';

/**
 * Outlines the interface for the Request objects.
 * 
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */
abstract class Services_TwitPic_Request_Common
{
    /**
     * Endpoint being used.
     * 
     * @var mixed
     */
    protected $uri;

    /**
     * Instance of Services_TwitPic.  Used
     * for getting options.
     * 
     * @var mixed
     */
    protected $twit;

    /**
     * Stores the endpoint uri and the instance of Services_TwitPic
     * 
     * @param string           $uri  Endpoint URI.
     * @param Services_TwitPic $twit Services_TwitPic instance
     * 
     * @return void
     */
    public function __construct($uri, Services_TwitPic $twit)
    {
        $this->uri  = $uri;
        $this->twit = $twit;
    }

    /**
     * Sets a POST variable.
     * 
     * @param string $field Field name
     * @param mixed  $value Field value
     * 
     * @return void
     */
    abstract public function setPostVar($field, $value);

    /**
     * Sets the image filename to be uploaded.
     * 
     * @param mixed $file Filename of the image
     * 
     * @return void
     */
    abstract public function setImage($file);

    /**
     * Actually sends the request.
     * 
     * @return void
     */
    abstract public function sendRequest();

    /**
     * Gets the response code.
     * 
     * @return int
     */
    abstract public function getResponseCode();

    /**
     * Gets the body of the response.
     * 
     * @return string
     */
    abstract public function getResponseBody();
}
?>
