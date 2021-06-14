# Hyper Framework
It's just for fun.

Clone this repo.

```bash
composer update
```

Create a simple page
Go to ```app/routes.php```

```php
$router->get("/", "Controllers\AppController:home");
$router->get("/404", "Controllers\AppController:error");

# Adding route
$router->get("/custom-route", "Controllers\AppController:customMethod");
```

Go to ```app/Controllers/AppController.php```

And add this method

```php
public function customMethod()
{
  $this->Template->render("customTemplate.twig");
}
```

Go to ```app/Views/customTemplate.twig```

And add edit your page.

Now, access your server, example: http://localhost/custom-route

Check it.

### References / Documentation
- https://github.com/vlucas/phpdotenv (For env)
- https://packagist.org/packages/coffeecode/router (For routes)
- https://twig.symfony.com/ (For template)