<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 */

namespace Acfatah\ObjectFactory;

use Psr\Container\ContainerInterface;
use Acfatah\ObjectFactory\Exception\InvalidArgumentException;

/**
 * Describes a class factory.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
abstract class AbstractFactory
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $constructorArgs;

    /**
     * @var array
     */
    private $methodCalls;

    /**
     * @var boolean
     */
    private $isSingle;

    /**
     * @var object
     */
    private $instance;

    /**
     * Class setter method.
     *
     * @param string $class
     * @return static
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Class getter method.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Constructor arguments setter method.
     *
     * @param array $constructorArgs
     * @return static
     */
    public function setConstructorArgs(array $constructorArgs)
    {
        $this->constructorArgs = $constructorArgs;

        return $this;
    }

    /**
     * Constructor arguments getter method.
     *
     * @return array
     */
    public function getConstructorArgs()
    {
        return $this->constructorArgs;
    }

    /**
     * MethodCalls setter method.
     *
     * @param array $methodCalls Method name as key and an array of values as argument.
     * @return static
     */
    public function setMethodCalls(array $methodCalls)
    {
        $this->methodCalls = $methodCalls;

        return $this;
    }

    /**
     * MethodCalls getter method.
     *
     * @return array
     */
    public function getMethodCalls()
    {
        return $this->methodCalls;
    }

    /**
     * SingleInstance setter method.
     *
     * @param boolean $singleInstance
     * @return static
     */
    public function setSingle($singleInstance)
    {
        $this->isSingle = boolval($singleInstance);

        return $this;
    }

    /**
     * Single getter method.
     *
     * @return boolean
     */
    public function isSingle()
    {
        return $this->isSingle;
    }

    /**
     * Instance setter method.
     *
     * @return string
     */
    public function setInstance($instance)
    {
        if (false === is_object($instance)) {
            $msg = "Argument supplied is not a class instance!";
            throw new InvalidArgumentException($msg);
        }

        $class = $this->getClass();

        if (false === $instance instanceof $class) {
            $msg = 'Argument supplied is not an instance of "%s"!';
            throw new InvalidArgumentException(sprintf($msg, $class));
        }

        $this->instance = $instance;

        return $this;
    }

    /**
     * Instance getter method.
     *
     * @return array
     */
    public function getInstance()
    {
        return $this->instance;
    }


    /**
     * Calls the setter methods after object creation.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return object
     */
    public function build(ContainerInterface $container)
    {
        $instance = $this->buildObject($container);

        $this->callSetterMethods($instance);

        return $instance;
    }

    /**
     * Resolves the class name to a class instance.
     *
     * @param \Psr\Container\ContainerInterface
     * @return object
     */
    abstract protected function buildObject(ContainerInterface $container);

    /**
     * Calls all the class instance setter methods.
     *
     * @param object $instance
     * @return static
     */
    protected function callSetterMethods($instance)
    {
        foreach ($this->getMethodCalls() as $method => $value) {
            call_user_func([$instance, $method], $value);
        }

        return $this;
    }
}
