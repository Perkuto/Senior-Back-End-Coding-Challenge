<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$classLoader = new \Doctrine\Common\ClassLoader(
                        'DoctrineExtensions', __DIR__."/../src/Acme/DemoBundle"
                        );
$classLoader->register();
return $loader;
