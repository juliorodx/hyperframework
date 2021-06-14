<?php

namespace Controllers;

use Lib\Template;

class AppController
{
  public function __construct($router)
  {
    $this->Router = $router;
    $this->Template = new Template(['debug' => true]);
  }

  public function home()
  {
    $vars['app']['title'] = "Hello";

    $this->Template->render("index.twig", $vars);
  }

  public function error()
  {
    $this->Template->render("404.twig");
  }
}
