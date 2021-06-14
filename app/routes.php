<?php

use CoffeeCode\Router\Router as Router;

/**
 * App routes
 *
 * @link https://packagist.org/packages/coffeecode/router
 */

$router = new Router($_ENV['APP_URL']);

$router->get("/", "Controllers\AppController:home");
$router->get("/404", "Controllers\AppController:error");


/**
 * This method executes the routes
 */
$router->dispatch();

/*
 * Redirect all errors
 */
if ($router->error()) {
  $router->redirect("/404");
}
