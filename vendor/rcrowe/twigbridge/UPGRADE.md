# TwigBridge Upgrade Guide

## Upgrade 0.5.x -> 0.6.x

There have been some big changes since the last 0.5.x release. 0.6.x now only supports PHP 5.4+ and is targeted for Laravel 4.2. It probably still works for L4.0/4.1, but this is not tested/supported.

Some of noticable changes are: 
 - The ServiceProvider has been renamed from `TwigBridge\TwigServiceProvider` to `TwigBridge\ServiceProvider`, so update this in your config/app.php
 - A Twig facade is added, see the install instructions.
 - The AliasLoader has been removed, Facades are not automatically called with `class_method(...)`. This has been replaced with:
   * Extensions for the most common Facades, which can be used with the same syntax (see readme for available extensions)
   * The Facade Loader Extension imports Facades which are configured in the package config, and can be called like `Class.method()`
   * The LegacyFacades Extension which can be enabled, to provide backward compatability with the 0.5 style Facades
 - The TwigBridge events are removed. Similar results can be achieved by simply using the Twig facade or IoC bindings
 - The Compile command has been removed. Templates are now automaticcaly compiled on the `php artisan optimize` command.
 - The package configuration has been split in 2 files. Republish the config and re-apply your settings.
 - The getAttributes() functions has changed, make sure you call attributes/methods correctly on your models. `model.attribute` will get the function, `model.attribute()` the Relation object. Other methods on models will also have to use parentheses.
 
For more changes see the changelog in CONTRIBUTING.md
