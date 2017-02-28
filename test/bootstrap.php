<?php

/**
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link https://github.com/acfatah/object-factory
 * /

/**
 * Phpunit bootstrap file
 * 
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Fixture\\', 'test/Fixture');
