<?php
/**
 * Services_TwitPic 
 * 
 * PHP Version 5.1.0+
 * 
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   New BSD
 * @link      http://trac.digg.internal/trac/digg
 */

require_once 'HTTP/Request.php';
require_once 'Services/TwitPic/Exception.php';

/**
 * Services_TwitPic 
 * 
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   New BSD
 * @link      http://trac.digg.internal/trac/digg
 */
class Services_TwitPic
{
    /**
     * uri 
     * 
     * URI of the TwitPic API server
     * 
     * @var string
     * @static
     * @access public
     */
    static public $uri = 'http://twitpic.com/api';

    /**
     * username 
     * 
     * @var string
     * @access protected
     */
    protected $username = '';
    /**
     * password 
     * 
     * @var string
     * @access protected
     */
    protected $password = '';

    /**
     * options 
     * 
     * @var array
     * @see setOptions()
     * @access protected
     */
    protected $options = array(
        'timeout'   => 30,
        'userAgent' => 'Services_TwitPic 0.1.0'
    );

    /**
     * __construct 
     * 
     * Set the username and password for requests.
     * 
     * @param mixed $username Twitter username
     * @param mixed $password Twitter password
     * 
     * @access public
     * @return void
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * upload 
     * 
     * Upload an image to TwitPic.
     * 
     * @param mixed $file Image file name to upload
     * 
     * @access public
     * @return mixed Results of sendRequest()
     */
    public function upload($file)
    {
        return $this->sendRequest('/upload', array('media' => $file));
    }

    /**
     * uploadAndPost 
     * 
     * Upload an image and optional message to TwitPic and post to Twitter.
     * 
     * @param mixed $file    Image file name to upload
     * @param mixed $message Optional message to include in the tweet
     * 
     * @access public
     * @return mixed Results of sendRequest()
     */
    public function uploadAndPost($file, $message = null)
    {
        $params = array('media' => $file);
        if ($message !== null) {
            $params['message'] = $message;
        }
        return $this->sendRequest('/uploadAndPost', $params);
    }

    /**
     * Send a request to the TwitPic API
     *
     * @param string $endPoint The API endpoint
     * @param array  $params   The API endpoint arguments to pass
     *
     * @throws Services_TwitPic_Exception on error
     * @return object Instance of SimpleXMLElement 
     */
    protected function sendRequest($endPoint, array $params)
    {
        $uri = self::$uri . $endPoint;

        $params['username'] = $this->username;
        $params['password'] = $this->password;

        $http = new HTTP_Request($uri,
                                 array('method'  => 'POST',
                                       'timeout' => $this->options['timeout'])
        );
        foreach ($params as $field => $value) {
            if ($field == 'media') {
                $http->addFile($field, $value);
                continue;
            }
            $http->addPostData($field, $value);
        }
        $http->addHeader('User-Agent', $this->options['userAgent']);
        $res = $http->sendRequest();
        if (PEAR::isError($res)) {
            throw new Servics_TwitPic_Exception($res->getMessage(), $res->getCode());
        }

        $code = $http->getResponseCode();
        $body = $http->getResponseBody();
        if ($code != 200) {
            throw new Services_TwitPic_Exception($body, $code);
        }

        if (!strlen($body)) {
            throw new Services_TwitPic_Exception(
                'Empty response was received from the API'
            );
        }

        $xml = @simplexml_load_string($body);
        if (!$xml instanceof SimpleXMLElement) {
            throw new Services_TwitPic_Exception(
                'Could not parse response received by the API: ' . $body
            );
        }

        if (isset($xml->err)) {
            throw new Services_TwitPic_Exception(
                (string)$xml->err->attributes()->msg,
                (int)$xml->err->attributes()->code
            );
        }

        return $xml;
    }

    /**
     * setOptions 
     * 
     * Override default options like timeout and user agent.
     * 
     * @param array $options Options to set
     * 
     * @see $options
     * @access public
     * @return void
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            }
        }
    }
}

?>