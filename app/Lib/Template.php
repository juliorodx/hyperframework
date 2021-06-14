<?php

/**
 * Template Class adapted to use Twig 3.x
 *
 * @link https://twig.symfony.com/doc/3.x/
 */

namespace Lib;

class Template
{

  public function __construct($options = [])
  {
    $renderer = new \Twig\Loader\FilesystemLoader(APP_ROOT . DS . 'Views');
    $twigOptions = [];

    if (isset($options['cache']) && $options['cache'] == true) {
      $twigOptions['cache'] = ROOT . DS . 'tmp' . DS . 'twig';
    }

    if (isset($options['debug']) && $options['debug'] == true) {
      $twigOptions['debug'] = true;
    }

    $twig = new \Twig\Environment($renderer, $twigOptions);

    $this->renderer = $twig;
  }

  public function addGlobal($var = null, $value = null)
  {
    return $this->renderer->addGlobal($var, $value);
  }

  public function addFunction($name = null, $fn)
  {
    $function = new \Twig\TwigFunction($name, $fn);
    return $this->renderer->addFunction($function);
  }

  public function render($tpl = null, $vars = [])
  {
    $file = APP_ROOT . DS . 'Views' . DS . $tpl;
    if (!file_exists($file)) die('Can\'t render ' . $tpl);
    die($this->renderer->render($tpl, $vars));
  }
}
