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

use Twig_SimpleFilter;

/**
 * Extension to expose defined filters to the Twig templates.
 *
 * See the `extensions.php` config file, specifically the `filters` key
 * to configure those that are loaded.
 */
class Filters extends Loader
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Loader_Filters';
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        $load    = $this->config->get('twigbridge.extensions.filters', []);
        $filters = [];

        foreach ($load as $method => $callable) {
            list($method, $callable, $options) = $this->parseCallable($method, $callable);

            $filter = new Twig_SimpleFilter(
                $method,
                function () use ($callable) {
                    return call_user_func_array($callable, func_get_args());
                },
                $options
            );

            $filters[] = $filter;
        }

        return $filters;
    }
}
