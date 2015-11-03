<?php

namespace Laravel\Lumen\Providers;

use Illuminate\Cache\Console\ClearCommand;
use Illuminate\Cache\CacheServiceProvider as BaseProvider;

class CacheServiceProvider extends BaseProvider
{
    /**
     * Register the cache related console commands.
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->app->singleton('command.cache.clear', function ($app) {
            return new ClearCommand($app['cache']);
        });

        $this->commands('command.cache.clear');
    }
}
