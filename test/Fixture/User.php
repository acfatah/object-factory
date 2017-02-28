<?php

namespace Fixture;

use Fixture\UserInterface;

class User implements UserInterface
{
    private $username;

    private $email;

    public function __construct($username = '', $email = '')
    {
        $this
            ->setUsername($username)
            ->setEmail($email);
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
