<?php

/**
 * Bootstrap
 */

if (isset($_ENV['ERROR_REPORTING'])) {
  error_reporting(constant($_ENV['ERROR_REPORTING']));
}

if(isset($_ENV['TIMEZONE']) && !empty($_ENV['TIMEZONE'])) {
  date_default_timezone_set($_ENV['TIMEZONE']);
}

/**
 * Start session
 */
session_start();