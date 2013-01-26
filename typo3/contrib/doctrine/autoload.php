<?php

// require psr-0 classloader
$basePath = dirname(__FILE__);
require_once $basePath . '/common/lib/Doctrine/Common/ClassLoader.php';

// register doctrine namespaces
$commonClassLoader = new \Doctrine\Common\ClassLoader('Doctrine\\Common', $basePath . '/common/lib/');
$commonClassLoader->register();
$dbalClassLoader = new \Doctrine\Common\ClassLoader('Doctrine\\DBAL', $basePath . '/dbal/lib/');
$dbalClassLoader->register();

?>