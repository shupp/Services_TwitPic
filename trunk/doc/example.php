<?php

require_once 'Services/TwitPic.php';

$user     = 'username';
$pass     = 'password';
$filename = '/home/shupp/testimage.jpg';

$twit = new Services_TwitPic($user, $pass);
try {
    $result = $twit->uploadAndPost($filename, 'testing image upload');
    print_r($result);
} catch (Services_TwitPic_Exception $e) {
    print_r($e->getMessage());
    print_r($e->getCode());
}

?>
