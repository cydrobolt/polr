# jQuery DataTables API for Laravel 4|5

[![Join the chat at https://gitter.im/yajra/laravel-datatables](https://badges.gitter.im/yajra/laravel-datatables.svg)](https://gitter.im/yajra/laravel-datatables?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

[![Laravel 4.2|5.0|5.1|5.2](https://img.shields.io/badge/Laravel-4.2|5.0|5.1|5.2-orange.svg)](http://laravel.com)
[![Latest Stable Version](https://poser.pugx.org/yajra/laravel-datatables-oracle/v/stable)](https://packagist.org/packages/yajra/laravel-datatables-oracle)
[![Build Status](https://travis-ci.org/yajra/laravel-datatables.svg?branch=master)](https://travis-ci.org/yajra/laravel-datatables)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yajra/laravel-datatables/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yajra/laravel-datatables/?branch=master)
[![Total Downloads](https://poser.pugx.org/yajra/laravel-datatables-oracle/downloads)](https://packagist.org/packages/yajra/laravel-datatables-oracle)
[![License](https://poser.pugx.org/yajra/laravel-datatables-oracle/license)](https://packagist.org/packages/yajra/laravel-datatables-oracle)

This package is created to handle [server-side](https://www.datatables.net/manual/server-side) works of [DataTables](http://datatables.net) jQuery Plugin via [AJAX option](https://datatables.net/reference/option/ajax) by using Eloquent ORM, Fluent Query Builder or Collection.

```php
use Yajra\Datatables\Facades\Datatables;

// Using Eloquent
return Datatables::eloquent(User::query())->make(true);

// Using Query Builder
return Datatables::queryBuilder(DB::table('users'))->make(true);

// Using Collection
return Datatables::collection(User::all())->make(true);

// Using the Engine Factory
return Datatables::of(User::query())->make(true);
return Datatables::of(DB::table('users'))->make(true);
return Datatables::of(User::all())->make(true);
```

## Requirements
- [PHP 5.5.9 or later](http://php.net/)
- [Laravel 5.0 or later](https://github.com/laravel/framework)
- [DataTables jQuery Plugin](http://datatables.net/) **Version 1.10.xx**

## Documentations
- You will find user friendly and updated documentation in the wiki here: [Laravel Datatables Wiki](https://github.com/yajra/laravel-datatables/wiki)
- You will find the API Documentation here: [Laravel Datatables API](http://yajra.github.io/laravel-datatables/api/)
- [Demo Application](http://datatables.yajrabox.com) is available for artisan's reference.

## Quick Installation
`composer require yajra/laravel-datatables-oracle:~6.0`

#### Service Provider
`Yajra\Datatables\DatatablesServiceProvider::class`

#### Facade
`Datatables` facade is automatically registered as an alias for `Yajra\Datatables\Facades\Datatables` class. 

#### Configuration and Assets
`$ php artisan vendor:publish --tag=datatables`

And that's it! Start building out some awesome DataTables!

## Upgrading from v5.x to v6.x
  - Change all occurrences of `yajra\Datatables` to `Yajra\Datatables`. (Use Sublime's find and replace all for faster update). 
  - Remove `Datatables` facade registration.
  - Temporarily comment out `Yajra\Datatables\DatatablesServiceProvider`.
  - Update package version on your composer.json and use `yajra/laravel-datatables-oracle: ~6.0`
  - Uncomment the provider `Yajra\Datatables\DatatablesServiceProvider`. 

## Debugging Mode
To enable debugging mode, just set `APP_DEBUG=true` and the package will include the queries and inputs used when processing the table.

**IMPORTANT:** Please make sure that APP_DEBUG is set to false when your app is on production.

## Contributing

Please see [CONTRIBUTING](https://github.com/yajra/laravel-datatables/blob/master/.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [aqangeles@gmail.com](mailto:aqangeles@gmail.com) instead of using the issue tracker.

## Credits

- [Arjay Angeles](https://github.com/yajra)
- [bllim/laravel4-datatables-package](https://github.com/bllim/laravel4-datatables-package)
- [All Contributors](https://github.com/yajra/laravel-datatables/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/yajra/laravel-datatables/blob/master/LICENSE.md) for more information.

## Buy me a beer
<a href='https://pledgie.com/campaigns/29515'><img alt='Click here to lend your support to: Laravel Datatables and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/29515.png?skin_name=chrome' border='0' ></a>
