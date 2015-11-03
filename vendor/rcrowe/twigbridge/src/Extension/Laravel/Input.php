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
use Illuminate\Http\Request;

/**
 * Access Laravels input class in your Twig templates.
 */
class Input extends Twig_Extension
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new input extension
     *
     * @param \Illuminate\Http\Request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Input';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('input_get', [$this->request, 'input']),
            new Twig_SimpleFunction('input_old', [$this->request, 'old']),
            new Twig_SimpleFunction('old', [$this->request, 'old']),
        ];
    }
}
