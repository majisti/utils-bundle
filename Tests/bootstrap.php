<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once $_SERVER['SYMFONY'].'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespace('Symfony', $_SERVER['SYMFONY']);
$loader->registerNamespace('Doctrine\ORM', $_SERVER['DOCTRINE_ORM']);
$loader->registerNamespace('Doctrine\DBAL', $_SERVER['DOCTRINE_DBAL']);
$loader->registerNamespace('Doctrine\Common\DataFixtures', $_SERVER['DOCTRINE_FIXTURES']);
$loader->registerNamespace('Doctrine\Common', $_SERVER['DOCTRINE_COMMON']);
$loader->register();

spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'Majisti\\UtilsBundle\\')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)).'.php';
        require_once __DIR__.'/../'.$path;
        return true;
    }
});
