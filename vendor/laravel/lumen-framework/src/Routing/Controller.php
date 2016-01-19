<?php

namespace Laravel\Lumen\Routing;

abstract class Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * The middleware defined on the controller.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Define a middleware on the controller.
     *
     * @param  string  $middleware
     * @param  array  $options
     * @return void
     */
    public function middleware($middleware, array $options = [])
    {
        $this->middleware[$middleware] = $options;
    }

    /**
     * Get the middleware for a given method.
     *
     * @param  string  $method
     * @return array
     */
    public function getMiddlewareForMethod($method)
    {
        $middleware = [];

        foreach ($this->middleware as $name => $options) {
            if (isset($options['only']) && ! in_array($method, (array) $options['only'])) {
                continue;
            }

            if (isset($options['except']) && in_array($method, (array) $options['except'])) {
                continue;
            }

            $middleware[] = $name;
        }

        return $middleware;
    }
}
