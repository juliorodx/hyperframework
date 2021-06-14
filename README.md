# Hyper Framework
It's just for fun.

Clone this repo.

```bash
composer update
```

Configure the application env

```bash
cp .env.example .env
```

Set the .htaccess (apache file config)
```bash
cp .htaccess.example .htaccess
```

Default ```.htacess``` file
```htaccess
<IfModule mod_rewrite.c>
  RewriteEngine On

  # Don't change it.
  RewriteBase /public/

  # ROUTER WWW Redirect. (If you want WWW redirection)
  #RewriteCond %{HTTP_HOST} !^www\. [NC]
  #RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [L]

  # ROUTER HTTPS Redirect (If you want HTTPS redirection)
  #RewriteCond %{HTTP:X-Forwarded-Proto} !https
  #RewriteCond %{HTTPS} off
  #RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L]

  # General route rewriting
  RewriteRule ^(.*)$ /public/index.php?route=/$1 [L,QSA]
</IfModule>
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

And add edit your page. (Example extending to layouts/main.twig)

```twig
{% extends 'layouts/main.twig' %}

{% block header %}
{% endblock %}

{% block content %}
  Your content here
{% endblock %}

{% block js %}
{% endblock %}
```

Now, access your server, example: http://localhost/custom-route

Check it.

### References / Documentation
- https://github.com/vlucas/phpdotenv (For env)
- https://packagist.org/packages/coffeecode/router (For routes)
- https://twig.symfony.com/ (For template)