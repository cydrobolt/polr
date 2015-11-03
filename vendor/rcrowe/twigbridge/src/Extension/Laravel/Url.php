<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge\Extension\Laravel;

use Twig_Extension;
use Twig_SimpleFunction;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str as IlluminateStr;
use Illuminate\Contracts\Routing\Registrar;
/**
 * Access Laravels url class in your Twig templates.
 */
class Url extends Twig_Extension
{
    /**
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new url extension
     *
     * @param \Illuminate\Routing\UrlGenerator
     * @param \Illuminate\Contracts\Routing\Registrar
     */
    public function __construct(UrlGenerator $url, Registrar $router)
    {
        $this->url = $url;
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Url';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('asset', [$this->url, 'asset'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('action', [$this->url, 'action'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('url', [$this, 'url'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('route', [$this->url, 'route'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('route_has', [$this->router, 'has'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('secure_url', [$this->url, 'secure'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('secure_asset', [$this->url, 'secureAsset'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction(
                'url_*',
                function ($name) {
                    $arguments = array_slice(func_get_args(), 1);
                    $name      = IlluminateStr::camel($name);

                    return call_user_func_array([$this->url, $name], $arguments);
                }
            ),
        ];
    }

    public function url($path = null, $parameters = [], $secure = null)
    {
        return $this->url->to($path, $parameters, $secure);
    }
}
