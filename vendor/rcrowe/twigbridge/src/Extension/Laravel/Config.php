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
use Illuminate\Config\Repository as ConfigRepository;

/**
 * Access Laravels config class in your Twig templates.
 */
class Config extends Twig_Extension
{
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Create a new config extension
     *
     * @param \Illuminate\Config\Repository
     */
    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Config';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('config', [$this->config, 'get']),
            new Twig_SimpleFunction('config_get', [$this->config, 'get']),
            new Twig_SimpleFunction('config_has', [$this->config, 'has']),
        ];
    }
}
