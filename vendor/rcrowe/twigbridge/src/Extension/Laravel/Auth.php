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
use Illuminate\Auth\AuthManager;

/**
 * Access Laravels auth class in your Twig templates.
 */
class Auth extends Twig_Extension
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Create a new auth extension.
     *
     * @param \Illuminate\Auth\AuthManager
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Auth';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('auth_check', [$this->auth, 'check']),
            new Twig_SimpleFunction('auth_guest', [$this->auth, 'guest']),
            new Twig_SimpleFunction('auth_user', [$this->auth, 'user']),
        ];
    }
}
