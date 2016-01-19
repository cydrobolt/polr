<?php

namespace Laravel\Lumen\Http;

use Laravel\Lumen\Application;
use Illuminate\Http\RedirectResponse;

class Redirector
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Create a new redirector instance.
     *
     * @param  Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Create a new redirect response to the given path.
     *
     * @param  string  $path
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     * @return \Illuminate\Http\RedirectResponse
     */
    public function to($path, $status = 302, $headers = [], $secure = null)
    {
        $path = $this->app->make('url')->to($path, [], $secure);

        return $this->createRedirect($path, $status, $headers);
    }

    /**
     * Create a new redirect response to a named route.
     *
     * @param  string  $route
     * @param  array   $parameters
     * @param  int     $status
     * @param  array   $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function route($route, $parameters = [], $status = 302, $headers = [])
    {
        $path = $this->app->make('url')->route($route, $parameters);

        return $this->to($path, $status, $headers);
    }

    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int    $status
     * @param  array  $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    public function back($status = 302, $headers = [])
    {
        $referrer = $this->app->make('request')
                                ->headers->get('referer');

        $url = $referrer ? $this->app->make('url')->to($referrer)
                                : $this->app->make('session')->previousUrl();

        return $this->createRedirect($url, $status, $headers);
    }

    /**
     * Create a new redirect response.
     *
     * @param  string  $path
     * @param  int     $status
     * @param  array   $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createRedirect($path, $status, $headers)
    {
        $redirect = new RedirectResponse($path, $status, $headers);

        $redirect->setRequest($this->app->make('request'));

        $redirect->setSession($this->app->make('session.store'));

        return $redirect;
    }
}
