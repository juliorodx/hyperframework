<?php

/**
 * Main loader
 */
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/autoload.php';
require __DIR__ . '/constants.php';

/**
 * Load .env
 */
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

/**
 * Bootstrap
 */
require __DIR__ . '/bootstrap.php';

/**
 * Get routes
 */
require __DIR__ . '/routes.php';