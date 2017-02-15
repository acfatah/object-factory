<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 */

namespace Acfatah\ObjectFactory\Exception;

use RuntimeException;
use Psr\Container\ContainerExceptionInterface;

/**
 * Base container exception
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class ContainerException extends RuntimeException implements ContainerExceptionInterface
{

}
