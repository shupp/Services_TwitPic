<?php

class Services_TwitPicTest_Mock extends Services_TwitPic
{
    public function getUserPass()
    {
        return array($this->username, $this->password);
    }

    public function getOptions()
    {
        return $this->options;
    }
}
