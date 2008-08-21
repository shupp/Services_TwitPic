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
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */

/**
 * @uses Services_TwitPic_Exception
 */
require_once 'Services/TwitPic/Exception.php';

/**
 * Services_TwitPic 
 * 
 * PHP Interface for uploading pictures to TwitPic API, as well as
 * posting the pictures with optional status messages to Twitter.
 * 
 * <code>
 * 
 * $user     = 'username';
 * $pass     = 'password';
 * $filename = '/Users/bill/Desktop/images.jpg';
 * 
 * $twit = new Services_TwitPic($user, $pass);
 * try {
 *     $twit->setOptions(array('timeout' => 10));
 *     $result = $twit->uploadAndPost($filename, 'testing image upload');
 *     print_r($result);
 * } catch (Services_TwitPic_Exception $e) {
 *     print_r($e->getMessage());
 *     print_r($e->getCode());
 * }
 * 
 * </code>
 * 
 * @category  Services
 * @package   Services_TwitPic
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2008 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://servicestwitpic.googlecode.com
 */
class Services_TwitPic
{
    /**
     *  HTTP Status OK.
     */
    const HTTP_STATUS_OK = 200;

    /**
     *  HTTP Status Internal Server Error.
     *  This code is only checked in testing.
     */
    const HTTP_STATUS_INTERNAL_ERROR = 500;

    /**
     * uri 
     * 
     * URI of the TwitPic API server
     * 
     * @var string
     */
    static public $uri = 'http://twitpic.com/api';

    /**
     * username 
     * 
     * Twitter username
     * 
     * @var string
     */
    protected $username = '';
    /**
     * password 
     * 
     * Twitter password
     * 
     * @var string
     */
    protected $password = '';

    /**
     * requestor 
     * 
     * Instance of the requestor (HTTP_Request or Mock) for doing the HTTP
     * transport.  Mock is used for testing, using canned responses.
     * 
     * @var mixed
     */
    protected $requestor;

    /**
     * options 
     * 
     * Options used by the requestor
     * 
     * @var array
     * @see setOptions(), getOption()
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
     * @param string $username  Twitter username
     * @param string $password  Twitter password
     * @param string $requestor Which requestor driver to use, defaults
     *                          to HTTPRequest
     */
    public function __construct($username, $password, $requestor = 'HTTPRequest')
    {
        $this->username  = $username;
        $this->password  = $password;
        $this->requestor = $requestor;
    }

    /**
     * upload 
     * 
     * Upload an image to TwitPic.
     * 
     * @param mixed $file Image file name to upload
     * 
     * @return mixed Results of {@link sendRequest()}
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
     * @return mixed Results of {@link sendRequest()}
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
     * Send a request to the TwitPic API.
     *
     * Note that the use of error suppression with simplexml_load_string() below is 
     * necessary as that function will throw a warning if the string being loaded is
     * not valid XML.  The documentation only indicates that it will return FALSE on
     * errors.
     *
     * @param string $endPoint The API endpoint
     * @param array  $params   The API endpoint arguments to pass
     *
     * @throws Services_TwitPic_Exception on error
     * @return SimpleXMLElement A SimpleXMLElement Object
     */
    protected function sendRequest($endPoint, array $params)
    {
        $uri = self::$uri . $endPoint;

        $params['username'] = $this->username;
        $params['password'] = $this->password;

        $requestor = $this->getRequestor($uri);

        foreach ($params as $field => $value) {
            if ($field == 'media') {
                $requestor->setImage($value);
                continue;
            }
            $requestor->setPostVar($field, $value);
        }

        $requestor->sendRequest();

        $code = $requestor->getResponseCode();
        $body = $requestor->getResponseBody();
        if ($code != self::HTTP_STATUS_OK) {
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

    /**
     * getOption 
     * 
     * Retrieve an option.
     * 
     * @param mixed $key Option to get
     * 
     * @throws InvalidArgumentException on error
     * @return mixed Option value
     */
    public function getOption($key)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }
        throw new InvalidArgumentException();
    }

    /**
     * getRequestor 
     * 
     * Instantiate the requestor.
     * 
     * @param string $uri Endpoint URI being queried.
     * 
     * @return TwitPic_Request_Common A TwitPic_Request_Common Object
     */
    protected function getRequestor($uri)
    {
        $class = 'Services_TwitPic_Request_' . $this->requestor;
        $file  = 'Services/TwitPic/Request/' . $this->requestor . '.php';
        include_once $file;
        if (!class_exists($class)) {
            throw new Services_TwitPic_Exception(
                'Class ' . $class . ' does not exist'
            );
        }
        return new $class($uri, $this);
    }
}

?>
