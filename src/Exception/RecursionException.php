<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 */

namespace Acfatah\ObjectFactory\Exception;

use Acfatah\ObjectFactory\Exception\ContainerException;

/**
 * Thrown when recursion occurs while resolving dependencies.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class RecursionException extends ContainerException
{

}
