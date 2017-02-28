<?php

namespace Fixture;

class Foo
{
    public function __construct(\NonExistingClass $foo)
    {
        ;
    }
}
