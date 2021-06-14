<?php

namespace Lib;

class Security
{

  public function createPassword($string = null)
  {
    if (!function_exists('password_hash') && !defined('PASSWORD_BCRYPT'))
      return null;

    return password_hash($string, PASSWORD_BCRYPT);
  }

  public function checkPassword($string = null, $hash = null)
  {
    if (!function_exists('password_verify') || !$string || !$hash)
      return false;

    return password_verify($string, $hash) ? true : false;
  }
}
