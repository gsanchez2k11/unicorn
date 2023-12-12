<?php

namespace ATDev\RocketChat\Tests\Users;

use Users\PreferencesResponseFixture1;

class ResponseFixture1 extends \stdClass
{
    public function __construct()
    {
        $this->_id = "asd123asd";
        $this->emails = [];
        $this->emails[0] = new \stdClass();
        $this->emails[0]->address = "test@example.com";
        $this->status = "some";
        $this->roles = ["admin", "guest"];
        $this->lastLogin = "2016-12-08T00:22:15.167Z";
        $this->utcOffset = -3.5;
        $this->avatarUrl = "https://localhost/avatar.png";
        $this->statusText = "status text";
    }
}

class ResponseFixture2 extends \stdClass
{
    public function __construct()
    {
        $this->createdAt = "2018-01-12T00:12:22.167Z";
        $this->type = "user";
        $this->active = true;
        $this->name = "John Doe";
        $this->statusConnection = "offline";
        $this->username = "jDoe";
        $this->settings = new \stdClass();
        $this->settings->preferences = new PreferencesResponseFixture1();
    }
}

class ResponseFixtureFull extends \stdClass
{
    public function __construct()
    {
        foreach ([new ResponseFixture1(), new ResponseFixture2()] as $fixture) {
            foreach ($fixture as $key => $value) {
                $this->{$key} = $value;
            }
        }

        $this->emails[0]->verified = true;
        $this->statusConnection = null;
        $this->connectionStatus = "online";
        $this->statusText = null;
        $this->message = "status message";
    }
}
