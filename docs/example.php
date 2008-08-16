<?php

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
