# Vindite Microframework

Vindite is a PHP micro-framework that helps you quickly write simple web applications.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install Vindite.

```bash
$ composer require vindite/vindite "dev-master@dev"
```

This will install Vindite and all required dependencies. Vindite requires PHP 7.2 or newer.

## Usage

Create an index.php file with the following contents:

```php
<?php

require 'vendor/autoload.php';

$app = Vindite\App::getInstance();

$app->route()->middleware([
    new Vindite\Middleware\Handler\Auth,
    new Vindite\Middleware\Handler\Session
])->group(function () use ($app) {

    $app->route()->get('/hello/{name}', function ($argument) use ($app) {
        return $app->json("Hello, {$argument['name']}");
    });

    $app->route()->get('/', 'HomeController@index');
    $app->route()->post('/store', 'HomeController@store');
    $app->route()->put('/put/{id}', 'HomeController@put');
    $app->route()->delete('/delete/{id}', 'HomeController@delete');
})->run();
```
## Examples

Please see https://github.com/vindite/vindite-skeleton for more examples.

## Credits

- [Vinicius Alves](https://github.com/vindite)

## License

The Vindite Microframework is licensed under the MIT license. See [License File](LICENSE.md) for more information.