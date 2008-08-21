<?php
/**
 * Services_TwitPic Example
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
 * @uses Services_TWitPic 
 */
require_once 'Services/TwitPic.php';

$user     = 'username';
$pass     = 'password';
$filename = '/Users/bill/Desktop/images.jpg';

$twit = new Services_TwitPic($user, $pass);
try {
    $twit->setOptions(array('timeout' => 10));
    $result = $twit->uploadAndPost($filename, 'testing image upload');
    print_r($result);
} catch (Services_TwitPic_Exception $e) {
    print_r($e->getMessage());
    print_r($e->getCode());
}

?>
