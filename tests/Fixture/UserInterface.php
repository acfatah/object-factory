<?php

namespace Fixture;

interface UserInterface
{
    public function setUsername($username);

    public function getUsername();

    public function setEmail($email);

    public function getEmail();
}
