<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 */

namespace Acfatah\ObjectFactory;

use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use Psr\Container\ContainerInterface;
use Acfatah\ObjectFactory\AbstractFactory;
use Acfatah\ObjectFactory\Exception\UnexpectedValueException;
use Acfatah\ObjectFactory\Exception\InvalidConstructorArgumentException;
use Acfatah\ObjectFactory\Exception\RecursionException;

/**
 * A class that creates an object.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class ObjectFactory extends AbstractFactory
{
    /**
     * @var string The class name.
     */
    protected $class;

    /**
     * @var array
     */
    protected $constructorArgs = [];

    /**
     * @var int Maximum recursion count of automatic resolution.
     */
    protected $maxRecursion;

    /**
     * @var array Recursion count.
     */
    private static $recursionCount;

    /**
     * Constructor.
     *
     * @param string $class
     * @param array $constructorArgs
     * @param array $methodCalls
     * @param boolean $lazy
     * @param boolean $single
     * @param int $maxRecursion
     */
    public function __construct(
        $class,
        array $constructorArgs = [],
        array $methodCalls = [],
        $single = false,
        $maxRecursion = 3
    ) {
        $this
            ->setClass($class)
            ->setConstructorArgs($constructorArgs)
            ->setMethodCalls($methodCalls)
            ->setSingle($single)
            ->setMaxRecursion($maxRecursion);
    }

    /**
     * MaxRecursion setter method.
     *
     * @param int $maxRecursion
     * @return static
     */
    public function setMaxRecursion($maxRecursion)
    {
        $this->maxRecursion = $maxRecursion;

        return $this;
    }

    /**
     * MaxRecursion getter method.
     *
     * @return int
     */
    public function getMaxRecursion()
    {
        return $this->maxRecursion;
    }

    public function buildObject(ContainerInterface $container)
    {
        if (null !== $this->getInstance()) {
            return $this->getInstance();
        }

        $instance = $this->resolveInstance($container);
        $this->resetCount();
        return $instance;
    }

    /**
     * Creates the class instance.
     *
     * @param \Psr\Container\ContainerInterface
     * @return mixed
     */
    protected function resolveInstance(ContainerInterface $container)
    {
        $this->increaseCount();
        $reflectionClass = new ReflectionClass($this->getClass());
        $constructorArgs = $reflectionClass->getConstructor();

        // resolve constructor arguments if any
        if (isset($constructorArgs)) {
            return $reflectionClass->newInstanceArgs(
                $this->resolveParameters($constructorArgs, $container)
            );
        }

        $instance = $reflectionClass->newInstance();

        // store single instance
        if ($this->isSingle()) {
            $this->setInstance($instance);
        }

        return $instance;
    }

    /**
     * Resolves an object parameters.
     *
     * @param \ReflectionMethod $constructor
     * @param \Psr\Container\ContainerInterface
     * @return array
     * @throws \Acfatah\Container\Exception\FactoryException
     */
    protected function resolveParameters(ReflectionMethod $constructor, $container)
    {
        if (!empty($this->getConstructorArgs())) {
            return $this->getConstructorArgs();
        }

        $arguments = [];

        /* @var $reflectionParameter \ReflectionParameter */
        foreach ($constructor->getParameters() as $reflectionParameter) {
            // use default value if available
            if ($reflectionParameter->isDefaultValueAvailable()) {
                $arguments[] = $reflectionParameter->getDefaultValue();
                continue;
            }

            // check if type-hint class exists
            try {
                $reflectionParameter->getClass();
            } catch (ReflectionException $re) {
                // rethrow as \Acfatah\Container\Exception\UnexpectedValueException
                $msg = 'Type-hint error with message "%s" from "%s" class constructor!';
                throw new UnexpectedValueException(sprintf(
                    $msg,
                    $re->getMessage(),
                    $reflectionParameter->getDeclaringClass()->getName()
                ));
            }

            // argument required but not a type-hinted class name
            if (!$reflectionParameter->getClass() instanceof ReflectionClass) {
                $msg = 'Unable to create constructor argument "%s" for'
                    . ' "%s" class!';
                throw new InvalidConstructorArgumentException(sprintf(
                    $msg,
                    $reflectionParameter->getPosition(),
                    $reflectionParameter->getDeclaringClass()->getName()
                ));
            }

            // resolve type-hint argument from container
            $arguments[] = $container->get(
                $reflectionParameter->getClass()->getName()
            );
        }
        return $arguments;
    }

    /**
     * Increases recursion count.
     *
     * @throws \Acfatah\Container\Exception\FactoryException
     */
    protected function increaseCount()
    {
        $class = $this->getClass();
        $max = $this->getMaxRecursion();

        // increment
        if (!isset(self::$recursionCount[$class])) {
            self::$recursionCount[$class] = 0;
        }
        self::$recursionCount[$class]++;

        if (self::$recursionCount[$class] > $max) {
            // throw exception if exceeds maximum count
            $msg = 'Class "%s" exceeds maximum recursion count of %s times!';
            throw new RecursionException(sprintf($msg, $class, $max));
        }
    }

    /**
     * Resets recursion count.
     */
    protected function resetCount()
    {
        self::$recursionCount[$this->getClass()] = 0;
    }
}
