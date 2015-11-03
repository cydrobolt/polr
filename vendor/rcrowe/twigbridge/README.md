Allows you to use [Twig](http://twig.sensiolabs.org/) seamlessly in [Laravel 5](http://laravel.com/).

[![Latest Stable Version](https://poser.pugx.org/rcrowe/twigbridge/v/stable.png)](https://packagist.org/packages/rcrowe/twigbridge)
[![Total Downloads](https://poser.pugx.org/rcrowe/twigbridge/downloads.png)](https://packagist.org/packages/rcrowe/twigbridge)
[![License](https://poser.pugx.org/rcrowe/twigbridge/license.png)](https://packagist.org/packages/rcrowe/twigbridge)

# Requirements

TwigBridge >=0.7 requires Laravel 5.

If you need to support for Laravel 4.1/4.2 checkout out TwigBridge 0.6.x, or 0.5.x for Laravel 4.0.

# Installation

Require this package with Composer

```bash
composer require rcrowe/twigbridge 0.8.x
```

Once Composer has installed or updated your packages you need to register TwigBridge with Laravel itself. Open up config/app.php and find the providers key towards the bottom and add:

```php
'TwigBridge\ServiceProvider',
```

You can add the TwigBridge Facade, to have easier access to the TwigBridge (or Twig_Environment).

```php
'Twig' => 'TwigBridge\Facade\Twig',
```

```php
Twig::addExtension('TwigBridge\Extension\Loader\Functions');
Twig::render('mytemplate', $data);
```

# Configuration

TwigBridge's configuration file can be extended in your ConfigServiceProvider, under the `twigbridge` key. You can find the default configuration file at `vendor/rcrowe/twigbridge/config`.  

You _should_ use Artisan to copy the default configuration file from the `/vendor` directory to `/config/twigbridge.php` with the following command:

```bash
php artisan vendor:publish --provider="TwigBridge\ServiceProvider"
```

If you make changes to the `/config/twigbridge.php` file you will most likely have to run the `twig:clean` Artisan command for the changes to take effect.

# Installation on Lumen

For Lumen, you need to load the same Service Provider, but you have to disable the `Auth`, `Translator` and `Url` extensions in your local configuration.
Copy the `config/twigbridge.php` file to your local `config` folder and register the configuration + Service Provider in `bootstrap/app.php`:

```php
$app->configure('twigbridge'); 
$app->register('TwigBridge\ServiceProvider');
```

# Usage

You call the Twig template like you would any other view:

```php
// Without the file extension
View::make('i_am_twig', [...])
```

TwigBridge also supports views in other packages:

```php
View::make('pagination::simple')
```

The above rules continue when extending another Twig template:

```html
{% extend "parent" %}
{% extend "pagination::parent" %}
```

You can call functions with parameters:

```html
{{ link_to_route('tasks.edit', 'Edit', task.id, {'class': 'btn btn-primary'}) }}
```

And output variables, escaped by default. Use the `raw` filter to skip escaping.

```html
{{ some_var }}
{{ html_var | raw }}
{{ long_var | str_limit(50) }}
```

# Extensions

Sometimes you want to extend / add new functions for use in Twig templates. Add to the `enabled` array in config/extensions.php a list of extensions for Twig to load.

```php
'enabled' => array(
    'TwigBridge\Extensions\Example'
)
```

TwigBridge supports both a string or a closure as a callback, so for example you might implement the [Assetic](https://github.com/kriswallsmith/assetic) Twig extension as follows:

```php
'enabled' => [
    function($app) {
        $factory = new Assetic\Factory\AssetFactory($app['path'].'/../some/path/');
        $factory->setDebug(false);
        // etc.....
        return new Assetic\Extension\Twig\AsseticExtension($factory);
    }
]
```

TwigBridge comes with the following extensions enabled by default:

- [Twig_Extension_Debug](http://twig.sensiolabs.org/doc/extensions/debug.html)
- TwigBridge\Extension\Laravel\Auth
- TwigBridge\Extension\Laravel\Config
- TwigBridge\Extension\Laravel\Dump
- TwigBridge\Extension\Laravel\Form
- TwigBridge\Extension\Laravel\Html
- TwigBridge\Extension\Laravel\Input
- TwigBridge\Extension\Laravel\Session
- TwigBridge\Extension\Laravel\String
- TwigBridge\Extension\Laravel\Translator
- TwigBridge\Extension\Laravel\Url
- TwigBridge\Extension\Loader\Facades
- TwigBridge\Extension\Loader\Filters
- TwigBridge\Extension\Loader\Functions

To enable '0.5.x' style Facades, enable the Legacy Facades extension:
- TwigBridge\Extension\Laravel\Legacy\Facades


## FilterLoader and FunctionLoader

These loader extensions exposes Laravel helpers as both Twig functions and filters.

Check out the config/extensions.php file to see a list of defined function / filters. You can also add your own.

## FacadeLoader

The FacadeLoader extension allows you to call any facade you have configured in config/extensions.php. This gives your Twig templates integration with any Laravel class as well as any other classes you alias.

To use the Laravel integration (or indeed any aliased class and method), just add your facades to the config and call them like `URL.to(link)` (instead of `URL::to($link)`)

## Functions/Filters/Variables

The following helpers/filters are added by the default Extensions. They are based on the helpers and/or facades, so should be self explaining.

Functions:
 * asset, action, url, route, secure_url, secure_asset
 * auth_check, auth_guest, auth_user
 * config_get, config_has
 * dump
 * form_* (All the Form::* methods, snake_cased)
 * html_* (All the Html::* methods, snake_cased)
 * input_get, input_old
 * link_to, link_to_asset, link_to_route, link_to_action
 * session_has, session_get, csrf_token
 * str_* (All the Str::* methods, snake_cased)
 * trans, trans_choice
 * url_* (All the URL::* methods, snake_cased)

Filters:
 * camel_case, snake_case, studly_case
 * str_* (All the Str::* methods, snake_cased)
 * trans, trans_choice

Global variables:
 * app: the Illuminate\Foundation\Application object
 * errors: The $errors MessageBag from the Validator (always available)

# Artisan Commands

TwigBridge offers a command for CLI Interaction.

Empty the Twig cache:
```
$ php artisan twig:clean
```

Lint all Twig templates:
```
$ php artisan twig:lint
```
