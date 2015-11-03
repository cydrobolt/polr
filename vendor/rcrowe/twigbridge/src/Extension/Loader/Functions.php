<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge\Extension\Loader;

use Twig_SimpleFunction;

/**
 * Extension to expose defined functions to the Twig templates.
 *
 * See the `extensions.php` config file, specifically the `functions` key
 * to configure those that are loaded.
 */
class Functions extends Loader
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Loader_Functions';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        $load      = $this->config->get('twigbridge.extensions.functions', []);
        $functions = [];

        foreach ($load as $method => $callable) {
            list($method, $callable, $options) = $this->parseCallable($method, $callable);

            $function = new Twig_SimpleFunction(
                $method,
                function () use ($callable) {
                    return call_user_func_array($callable, func_get_args());
                },
                $options
            );

            $functions[] = $function;
        }

        return $functions;
    }
}
