<?php

use Mockery as m;
use Illuminate\Http\Request;
use Laravel\Lumen\Application;

class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testBasicRequest()
    {
        $app = new Application;

        $app->get('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testRequestWithoutSymfonyClass()
    {
        $app = new Application;

        $app->get('/', function () {
            return response('Hello World');
        });

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $response = $app->dispatch();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());

        unset($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    public function testRequestWithoutSymfonyClassTrailingSlash()
    {
        $app = new Application;

        $app->get('/foo', function () {
            return response('Hello World');
        });

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo/';

        $response = $app->dispatch();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());

        unset($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    public function testRequestWithParameters()
    {
        $app = new Application;

        $app->get('/foo/{bar}/{baz}', function ($bar, $baz) {
            return response($bar.$baz);
        });

        $response = $app->handle(Request::create('/foo/1/2', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('12', $response->getContent());
    }

    public function testRequestToControllerWithParameters()
    {
        $app = new Application;

        $app->get('/foo/{bar}', 'LumenTestController@actionWithParameter');

        $response = $app->handle(Request::create('/foo/1', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('1', $response->getContent());
    }

    public function testCallbackRouteWithDefaultParameter()
    {
        $app = new Application;
        $app->get('/foo-bar/{baz}', function ($baz = 'default-value') {
          return response($baz);
        });

        $response = $app->handle(Request::create('/foo-bar/something', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('something', $response->getContent());
    }

    public function testControllerRouteWithDefaultParameter()
    {
        $app = new Application;
        $app->get('/foo-bar/{baz}', 'LumenTestController@actionWithDefaultParameter');

        $response = $app->handle(Request::create('/foo-bar/something2', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('something2', $response->getContent());
    }

    public function testGlobalMiddleware()
    {
        $app = new Application;

        $app->middleware(['LumenTestMiddleware']);

        $app->get('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Middleware', $response->getContent());
    }

    public function testRouteMiddleware()
    {
        $app = new Application;

        $app->routeMiddleware(['foo' => 'LumenTestMiddleware']);

        $app->get('/', function () {
            return response('Hello World');
        });

        $app->get('/foo', ['middleware' => 'foo', function () {
            return response('Hello World');
        }]);

        $response = $app->handle(Request::create('/', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());

        $response = $app->handle(Request::create('/foo', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Middleware', $response->getContent());
    }

    public function testGlobalMiddlewareParameters()
    {
        $app = new Application;

        $app->middleware(['LumenTestParameterizedMiddleware:foo,bar']);

        $app->get('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Middleware - foo - bar', $response->getContent());
    }

    public function testRouteMiddlewareParameters()
    {
        $app = new Application;

        $app->routeMiddleware(['foo' => 'LumenTestParameterizedMiddleware']);

        $app->get('/', ['middleware' => 'foo:bar,boom', function () {
            return response('Hello World');
        }]);

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Middleware - bar - boom', $response->getContent());
    }

    public function testGroupRouteMiddleware()
    {
        $app = new Application;

        $app->routeMiddleware(['foo' => 'LumenTestMiddleware']);

        $app->group(['middleware' => 'foo'], function ($app) {
            $app->get('/', function () {
                return 'Hello World';
            });
            $app->group([], function () {});
            $app->get('/fooBar', function () {
                return 'Hello World';
            });
        });

        $app->get('/foo', function () {
            return 'Hello World';
        });

        $response = $app->handle(Request::create('/', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Middleware', $response->getContent());

        $response = $app->handle(Request::create('/foo', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());

        $response = $app->handle(Request::create('/fooBar', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Middleware', $response->getContent());
    }

    public function testWithMiddlewareDisabled()
    {
        $app = new Application;

        $app->middleware(['LumenTestMiddleware']);
        $app->instance('middleware.disable', true);

        $app->get('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testGroupPrefixRoutes()
    {
        $app = new Application;

        $app->group(['prefix' => 'user'], function ($app) {
            $app->get('/', function () {
                return response('User Index');
            });

            $app->get('profile', function () {
                return response('User Profile');
            });

            $app->get('/show', function () {
                return response('User Show');
            });
        });

        $response = $app->handle(Request::create('/user', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User Index', $response->getContent());

        $response = $app->handle(Request::create('/user/profile', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User Profile', $response->getContent());

        $response = $app->handle(Request::create('/user/show', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('User Show', $response->getContent());
    }

    public function testNotFoundResponse()
    {
        $app = new Application;
        $app->instance('Illuminate\Contracts\Debug\ExceptionHandler', $mock = m::mock('Laravel\Lumen\Exceptions\Handler[report]'));
        $mock->shouldIgnoreMissing();

        $app->get('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/foo', 'GET'));

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testMethodNotAllowedResponse()
    {
        $app = new Application;
        $app->instance('Illuminate\Contracts\Debug\ExceptionHandler', $mock = m::mock('Laravel\Lumen\Exceptions\Handler[report]'));
        $mock->shouldIgnoreMissing();

        $app->post('/', function () {
            return response('Hello World');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testControllerResponse()
    {
        $app = new Application;

        $app->get('/', 'LumenTestController@action');

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('LumenTestController', $response->getContent());
    }

    public function testNamespacedControllerResponse()
    {
        $app = new Application;

        require_once __DIR__.'/fixtures/TestController.php';

        $app->group(['namespace' => 'Lumen\Tests'], function ($app) {
            $app->get('/', 'TestController@action');
            $app->group([], function () {});
            $app->get('/foo', 'TestController@action');
        });

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Lumen\Tests\TestController', $response->getContent());

        $response = $app->handle(Request::create('/foo', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Lumen\Tests\TestController', $response->getContent());
    }

    public function testGeneratingUrls()
    {
        $app = new Application;
        $app->instance('request', Request::create('http://lumen.laravel.com', 'GET'));
        unset($app->availableBindings['request']);

        $app->get('/foo-bar', ['as' => 'foo', function () {
            //
        }]);

        $app->get('/foo-bar/{baz}/{boom}', ['as' => 'bar', function () {
            //
        }]);

        $this->assertEquals('http://lumen.laravel.com/something', url('something'));
        $this->assertEquals('http://lumen.laravel.com/foo-bar', route('foo'));
        $this->assertEquals('http://lumen.laravel.com/foo-bar/1/2', route('bar', ['baz' => 1, 'boom' => 2]));
        $this->assertEquals('http://lumen.laravel.com/foo-bar?baz=1&boom=2', route('foo', ['baz' => 1, 'boom' => 2]));
    }

    public function testGeneratingUrlsForRegexParameters()
    {
        $app = new Application;
        $app->instance('request', Request::create('http://lumen.laravel.com', 'GET'));
        unset($app->availableBindings['request']);

        $app->get('/foo-bar', ['as' => 'foo', function () {
            //
        }]);

        $app->get('/foo-bar/{baz:[0-9]+}/{boom}', ['as' => 'bar', function () {
            //
        }]);

        $app->get('/foo-bar/{baz:[0-9]+}/{boom:[0-9]+}', ['as' => 'baz', function () {
            //
        }]);

        $app->get('/foo-bar/{baz:[0-9]{2,5}}', ['as' => 'boom', function () {
            //
        }]);

        $this->assertEquals('http://lumen.laravel.com/something', url('something'));
        $this->assertEquals('http://lumen.laravel.com/foo-bar', route('foo'));
        $this->assertEquals('http://lumen.laravel.com/foo-bar/1/2', route('bar', ['baz' => 1, 'boom' => 2]));
        $this->assertEquals('http://lumen.laravel.com/foo-bar/1/2', route('baz', ['baz' => 1, 'boom' => 2]));
        $this->assertEquals('http://lumen.laravel.com/foo-bar/{baz:[0-9]+}/{boom:[0-9]+}?ba=1&bo=2', route('baz', ['ba' => 1, 'bo' => 2]));
        $this->assertEquals('http://lumen.laravel.com/foo-bar/5', route('boom', ['baz' => 5]));
    }

    public function testRegisterServiceProvider()
    {
        $app = new Application;
        $provider = new LumenTestServiceProvider($app);
        $app->register($provider);
    }

    public function testUsingCustomDispatcher()
    {
        $routes = new FastRoute\RouteCollector(new FastRoute\RouteParser\Std, new FastRoute\DataGenerator\GroupCountBased);

        $routes->addRoute('GET', '/', [function () {
            return response('Hello World');
        }]);

        $app = new Application;

        $app->setDispatcher(new FastRoute\Dispatcher\GroupCountBased($routes->getData()));

        $response = $app->handle(Request::create('/', 'GET'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

    public function testMiddlewareReceiveResponsesEvenWhenStringReturned()
    {
        unset($_SERVER['__middleware.response']);

        $app = new Application;

        $app->routeMiddleware(['foo' => 'LumenTestPlainMiddleware']);

        $app->get('/', ['middleware' => 'foo', function () {
            return 'Hello World';
        }]);

        $response = $app->handle(Request::create('/', 'GET'));
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
        $this->assertEquals(true, $_SERVER['__middleware.response']);
    }

    public function testEnvironmentDetection()
    {
        $app = new Application;

        $this->assertEquals('production', $app->environment());
        $this->assertTrue($app->environment('production'));
        $this->assertTrue($app->environment(['production']));
    }
}

class LumenTestService
{
}

class LumenTestServiceProvider extends Illuminate\Support\ServiceProvider
{
    public function register()
    {
    }
}

class LumenTestMiddleware
{
    public function handle($request, $next)
    {
        return response('Middleware');
    }
}

class LumenTestPlainMiddleware
{
    public function handle($request, $next)
    {
        $response = $next($request);
        $_SERVER['__middleware.response'] = $response instanceof Illuminate\Http\Response;

        return $response;
    }
}

class LumenTestParameterizedMiddleware
{
    public function handle($request, $next, $parameter1, $parameter2)
    {
        return response("Middleware - $parameter1 - $parameter2");
    }
}

class LumenTestController
{
    public $service;

    public function __construct(LumenTestService $service)
    {
        $this->service = $service;
    }

    public function action()
    {
        return response(__CLASS__);
    }

    public function actionWithParameter($baz)
    {
        return response($baz);
    }

    public function actionWithDefaultParameter($baz = 'default-value')
    {
        return response($baz);
    }
}
