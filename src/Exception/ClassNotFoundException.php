<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 */

namespace Acfatah\ObjectFactory\Exception;

use Psr\Container\NotFoundExceptionInterface;
use Acfatah\ObjectFactory\Exception\ContainerException;

/**
 * @link https://github.com/container-interop/container-interop/blob/master/src/Interop/Container/Exception/NotFoundException.php \Psr\ContainerNotFoundExceptionInterface
 */
class ClassNotFoundException extends ContainerException implements NotFoundExceptionInterface
{

}
