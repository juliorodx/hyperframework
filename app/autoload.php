<?php
/**
 * Autoload
 */
spl_autoload_register(function($class) {
  $classUpper = lcfirst($class);
  $className = str_replace(['\\'], ['/'], $classUpper);
  $classPath = __DIR__ . '/' . $className . '.php';
  if(!file_exists($classPath)) die('Can\'t initiliaze ' . $class);
  require $classPath;
});