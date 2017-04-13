<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 */

namespace Acfatah\ObjectFactory;

use Acfatah\ObjectFactory\AbstractFactory;
use Acfatah\ObjectFactory\ObjectFactory;
use Acfatah\ObjectFactory\Exception\ClassNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Container used to store object factories.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class Container implements ContainerInterface
{
    /**
     * @var array \Acfatah\ObjectFactory\ObjectFactory
     */
    protected $factories = [];

    /**
     * Adds an object factory and returns it.
     *
     * @param string $class
     * @return \Acfatah\ObjectFactory\ObjectFactory
     */
    public function add($class)
    {
        $this->prepareNamespace($class);
        $factory = new ObjectFactory($class);
        $this->set($class, $factory);

        return $factory;
    }

    /**
     * Sets a factory to an interface or a class name.
     *
     * @param string $class
     * @param \Acfatah\ObjectFactory\AbstractFactory $factory
     */
    public function set($class, AbstractFactory $factory)
    {
        $this->factories[$class] = $factory;

        return $this;
    }

    public function has($class)
    {
        $this->prepareNamespace($class);

        return isset($this->factories[$class]);
    }

    public function get($class)
    {
        $this->prepareNamespace($class);

        if (!interface_exists($class) && !class_exists($class)) {
            $msg = 'Class "%s" does not exists!';
            throw new ClassNotFoundException(sprintf($msg, $class));
        }

        if (!$this->has($class)) {
            $this->add($class);
        }

        return $this->factories[$class]->build($this);
    }

    /**
     * Prepares class namespace.
     *
     * @param string $class
     */
    protected function prepareNamespace(&$class)
    {
        $class = '\\' . trim($class, '\\');

        return $this;
    }
}
