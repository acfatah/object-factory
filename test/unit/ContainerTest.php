<?php

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new \Acfatah\ObjectFactory\Container;
    }

    public function createEmptyCont()
    {
        return new \Acfatah\ObjectFactory\Container;
    }

    public function testAddMethodReturnsFactory()
    {
        $this->assertInstanceOf(
            '\Acfatah\ObjectFactory\ObjectFactory',
            $builder = $this->createEmptyCont()->add('\Fixture\User')
        );
    }

    public function testBuild()
    {
        $cont = $this->createEmptyCont();

        $this->assertFalse($cont->has('\stdClass'));

        $actual = $cont->get('\stdClass');

        $this->assertInstanceOf('\stdClass', $actual);
    }

    public function testBuildNormal()
    {
        $cont = $this->createEmptyCont();

        $cont->add('\stdClass');

        $this->assertTrue($cont->has('\stdClass'));

        $object_1 = $cont->get('\stdClass');
        $object_2 = $cont->get('\stdClass');

        $this->assertNotSame($object_2, $object_1);
    }

    public function testBuildSingle()
    {
        $cont = $this->createEmptyCont();
        $cont
            ->add('\stdClass')
                ->setSingle(true);

        $this->assertTrue($cont->has('\stdClass'));

        $object_1 = $cont->get('\stdClass');
        $object_2 = $cont->get('\stdClass');

        $this->assertSame($object_2, $object_1);
    }

    public function testBuildWithConstructorArguments()
    {
        $expected = [
            'admin',
            'admin@email.com'
        ];

        $cont = $this->createEmptyCont();
        $cont
            ->add('\Fixture\User')
                ->setConstructorArgs($expected);

        /* @var $user \Fixture\User */
        $user = $cont->get('\Fixture\User');

        $this->assertEquals($expected[0], $user->getUsername());
        $this->assertEquals($expected[1], $user->getEmail());
    }

    public function testBuildWithMethodCalls()
    {
        $expected = [
            'admin',
            'admin@email.com'
        ];

        $cont = $this->createEmptyCont();
        $cont
            ->add('\Fixture\User')
                ->setMethodCalls([
                    'setUsername' => $expected[0],
                    'setEmail' => $expected[1]
                ]);

        /* @var $user \Fixture\User */
        $user = $cont->get('\Fixture\User');

        $this->assertEquals($expected[0], $user->getUsername());
        $this->assertEquals($expected[1], $user->getEmail());
    }

    public function testBuildByInterfaceBinding()
    {
        $cont = $this->createEmptyCont();
        $cont
            ->add('\Fixture\UserInterface')
                ->setClass('\Fixture\User');

        $this->assertInstanceOf('\Fixture\User', $cont->get('\Fixture\UserInterface'));
    }

    public function testBuildByAutomaticResolution()
    {
        $cont = $this->createEmptyCont();
        /* @var $errHandler \Fixture\ErrorHandler */
        $errHandler = $cont->get('\Fixture\ErrorHandler');

        $this->assertInstanceOf('\Fixture\Logger', $errHandler->getLogger());
    }

    public function testExceptionClassNotFound()
    {
        $this->expectException('\Acfatah\ObjectFactory\Exception\ClassNotFoundException');

        $cont = $this->createEmptyCont();
        $cont->get('NonExistingClass');
    }

    /**
     * Non type-hinted constructor arguments
     */
    public function testExceptionInvalidConstructorArgs()
    {
        $this->expectException('\Acfatah\ObjectFactory\Exception\InvalidConstructorArgumentException');

        $cont = $this->createEmptyCont();
        $cont->get('\PDO');
    }

    /**
     * Type-hinted class does not exist
     */
    public function testExceptionTypeHintClassNotExists()
    {
        $this->expectException('\Acfatah\ObjectFactory\Exception\UnexpectedValueException');

        $cont = $this->createEmptyCont();
        $cont->get('\Fixture\Foo');
    }

    public function testExceptionInfiniteRecursion()
    {
        $this->expectException('\Acfatah\ObjectFactory\Exception\RecursionException');

        $cont = $this->createEmptyCont();
        $cont->get('\Fixture\InfiniteRecursion');
    }
}
