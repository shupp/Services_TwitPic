<?php
/**
 * Services_TwitPic_Request_Common 
 * 
 * PHP Version 5.1.0+
 * 
 * @abstract
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   New BSD
 * @link      http://servicestwitpic.googlecode.com
 */

/**
 * Services_TwitPic_Request_Common 
 * 
 * Outline the interface for the Request objects.
 * 
 * @abstract
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   New BSD
 * @link      http://servicestwitpic.googlecode.com
 */
abstract class Services_TwitPic_Request_Common
{
    /**
     * uri 
     * 
     * Endpoint being used.
     * 
     * @var mixed
     * @access protected
     */
    protected $uri;

    /**
     * twit 
     * 
     * Instance of Services_TwitPic.  Used
     * for getting options.
     * 
     * @var mixed
     * @access protected
     */
    protected $twit;

    /**
     * postVars 
     * 
     * Store the 
     * 
     * @var mixed
     * @access protected
     */

    /**
     * __construct 
     * 
     * Store the endpoing uri and the instance of Services_TwitPic
     * 
     * @param string $uri  Endpoint URI.
     * @param object $twit Services_TwitPic instance
     * 
     * @access public
     * @return void
     */
    public function __construct($uri, Services_TwitPic $twit)
    {
        $this->uri  = $uri;
        $this->twit = $twit;
    }

    /**
     * setPostVar 
     * 
     * Set POST variables.
     * 
     * @param string $field Field name
     * @param mixed  $value Field value
     * 
     * @abstract
     * @access public
     * @return void
     */
    abstract public function setPostVar($field, $value);

    /**
     * setImage 
     * 
     * Set the image filename to be uploaded.
     * 
     * @param mixed $file Filename of the image
     * 
     * @abstract
     * @access public
     * @return void
     */
    abstract public function setImage($file);

    /**
     * sendRequest 
     * 
     * Actually send the request.
     * 
     * @abstract
     * @access public
     * @return void
     */
    abstract public function sendRequest();

    /**
     * getResponseCode 
     * 
     * Get the response code.
     * 
     * @abstract
     * @access public
     * @return int
     */
    abstract public function getResponseCode();

    /**
     * getResponseBody 
     * 
     * Get the body of the response.
     * 
     * @abstract
     * @access public
     * @return string
     */
    abstract public function getResponseBody();
}
?>
