<?php
/**
 * Services_TwitPic_Request_HTTPRequest 
 * 
 * PHP Version 5.1.0+
 * 
 * @uses      Services_TwitPic_Request_Common
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */

/**
 * @uses Services_TwitPic_Request_Common 
 */
require_once 'Services/TwitPic/Request/Common.php';

/**
 * @uses HTTP_Request
 */
require_once 'HTTP/Request.php';

/**
 * Services_TwitPic_Request_HTTPRequest 
 * 
 * HTTP_Request driver.
 * 
 * @uses      Services_TwitPic_Request_Common
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */
class Services_TwitPic_Request_HTTPRequest extends Services_TwitPic_Request_Common
{
    /**
     * httpRequest 
     * 
     * Instance of HTTP_Request
     * 
     * @var object
     */
    protected $httpRequest;

    /**
     * __construct 
     * 
     * Set the options, endpoint, and instatiate HTTP_Request
     * 
     * @param string           $uri  URI of the API endpoint
     * @param Services_TwitPic $twit Instance of Services_TwitPic
     * 
     * @return void
     */
    public function __construct($uri, Services_TwitPic $twit)
    {
        parent::__construct($uri, $twit);
        $options = array('method'  => 'POST',
                         'timeout' => $this->twit->getOption('timeout'));

        $this->httpRequest = new HTTP_Request($uri, $options);
    }

    /**
     * setPostVar 
     * 
     * Set a POST variable to be sent.
     * 
     * @param string $field Field name
     * @param mixed  $value Field value
     * 
     * @return void
     */
    public function setPostVar($field, $value)
    {
        $this->httpRequest->addPostData($field, $value);
    }

    /**
     * setImage 
     * 
     * Set the image filename to be uploaded.
     * 
     * @param mixed $file Filename of image.
     * 
     * @return void
     */
    public function setImage($file)
    {
        $this->httpRequest->addFile('media', $file);
    }

    /**
     * sendRequest 
     * 
     * Set the User-Agent and actually send the request to the TwitPic API.
     * 
     * @throws Services_TwitPic_Exception on failure
     * @return void
     */
    public function sendRequest()
    {
        $this->httpRequest->addHeader('User-Agent',
                                      $this->twit->getOption('userAgent'));
        $res = $this->httpRequest->sendRequest();
        if (PEAR::isError($res)) {
            throw new Servics_TwitPic_Exception($res->getMessage(), $res->getCode());
        }
    }

    /**
     * getResponseCode 
     * 
     * Get the response code of the request.
     * 
     * @return int
     */
    public function getResponseCode()
    {
        return $this->httpRequest->getResponseCode();
    }

    /**
     * getResponseBody 
     * 
     * Get the body of the response.
     * 
     * @return string
     */
    public function getResponseBody()
    {
        return $this->httpRequest->getResponseBody();
    }
}
?>
