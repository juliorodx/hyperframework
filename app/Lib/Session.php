<?php

namespace Lib;

class Session
{

  public function set($key, $value = null)
  {
    if (!isset($key) || empty($key)) return false;

    $_SESSION[$key] = $value;
    return true;
  }

  public function get($key)
  {
    if (!isset($_SESSION[$key])) return null;

    return $_SESSION[$key];
  }

  public function unset($key)
  {
    if (isset($_SESSION[$key])) {
      unset($_SESSION[$key]);
      return true;
    }

    return false;
  }

  public function setFlash($value = null)
  {
    if (!$value) return false;

    return $this->set('flash_message', $value);
  }

  public function getFlash()
  {
    if (!isset($_SESSION['flash_message'])) return null;

    $flash = $this->get('flash_message');
    $this->unset('flash_message');
    return $flash;
  }
}
