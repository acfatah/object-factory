<?php

class ObjectFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function testSetInstanceMethodInvalidArgumentType()
    {
        $this->expectException('\Acfatah\ObjectFactory\Exception\InvalidArgumentException');

        $factory = new Acfatah\ObjectFactory\ObjectFactory('stdClass');
        $factory->setInstance([]); // invalid
    }

    public function testSetInstanceMethodInvalidClassInstance()
    {
        $this->expectException('\Acfatah\ObjectFactory\Exception\InvalidArgumentException');

        $factory = new Acfatah\ObjectFactory\ObjectFactory('stdClass');
        $factory->setInstance(new \Fixture\User); // invalid
    }
}
