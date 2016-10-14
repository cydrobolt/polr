<?php

namespace Collective\Html;

use Illuminate\Support\ServiceProvider;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlBuilder();

        $this->registerFormBuilder();

        $this->app->alias('html', 'Collective\Html\HtmlBuilder');
        $this->app->alias('form', 'Collective\Html\FormBuilder');
    }

    /**
     * Register the HTML builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new HtmlBuilder($app['url']);
        });
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['html', 'form', 'Collective\Html\HtmlBuilder', 'Collective\Html\FormBuilder'];
    }
}
