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
use Illuminate\Session\SessionManager;

/**
 * Access Laravels session class in your Twig templates.
 */
class Session extends Twig_Extension
{
    /**
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;

    /**
     * Create a new session extension
     *
     * @param \Illuminate\Session\SessionManager
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Session';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('session', [$this->session, 'get']),
            new Twig_SimpleFunction('csrf_token', [$this->session, 'token'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('csrf_field', 'csrf_field', ['is_safe' => ['html']]),
            new Twig_SimpleFunction('session_get', [$this->session, 'get']),
            new Twig_SimpleFunction('session_pull', [$this->session, 'pull']),
            new Twig_SimpleFunction('session_has', [$this->session, 'has']),
        ];
    }
}
