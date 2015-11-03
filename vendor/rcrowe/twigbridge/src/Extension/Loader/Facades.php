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

use TwigBridge\Extension\Loader\Facade\Caller;

/**
 * Extension to expose defined facades to the Twig templates.
 *
 * See the `extensions.php` config file, specifically the `facades` key
 * to configure those that are loaded.
 *
 * Use the following syntax for using a facade in your application.
 *
 * <code>
 *     {{ Facade.method(param, ...) }}
 *     {{ Config.get('app.timezone') }}
 * </code>
 */
class Facades extends Loader
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Loader_Facades';
    }

    /**
     * {@inheritDoc}
     */
    public function getGlobals()
    {
        $load    = $this->config->get('twigbridge.extensions.facades', []);
        $globals = [];

        foreach ($load as $facade => $options) {
            list($facade, $callable, $options) = $this->parseCallable($facade, $options);

            $globals[$facade] = new Caller($callable, $options);
        }

        return $globals;
    }
}
