<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge\Extension\Laravel\Legacy;

use Twig_Extension;
use Twig_Function_Function;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;

/**
 * Handles undefined function calls in a Twig template by checking
 * if the function corresponds to an aliased class defined in your
 * app config file.
 *
 * {{ auth_check() }} would call Auth::check()
 */
class Facades extends Twig_Extension
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var array Lookup cache
     */
    protected $lookup = [];

    /**
     * @var array Aliases loaded by Illuminate.
     */
    protected $aliases;

    /**
     * @var array Shortcut to alias map.
     */
    protected $shortcuts;

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Legacy_Facades';
    }

    /**
     * Create a new legacy facade extension
     *
     * @param \Illuminate\Foundation\Application
     * @param \Illuminate\Config\Repository
     */
    public function __construct(Application $app, Config $config)
    {
        $this->app    = $app;
        $this->config = $config;

        $aliases   = $this->config->get('app.aliases', []);
        $shortcuts = $this->config->get('twigbridge.alias_shortcuts', []);

        $this->setAliases($aliases);
        $this->setShortcuts($shortcuts);

        // Register Twig callback to handle undefined functions
        $this->app['twig']->registerUndefinedFunctionCallback(function ($name) {
            // Allow any method on aliased classes
            return $this->getFunction($name);
        });
    }

    /**
     * Sets those classes that have been Aliased.
     *
     * @param array $aliases Aliased classes.
     *
     * @return void
     */
    public function setAliases(array $aliases)
    {
        $this->aliases = array_change_key_case($aliases, CASE_LOWER);
    }

    /**
     * Get aliased classes.
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Set shortcuts to aliased classes.
     *
     * @param array $shortcuts Shortcut to alias map.
     *
     * @return void
     */
    public function setShortcuts(array $shortcuts)
    {
        $lowered = [];

        foreach ($shortcuts as $from => $to) {
            $lowered[strtolower($from)] = strtolower($to);
        }

        $this->shortcuts = $lowered;
    }

    /**
     * Get alias shortcuts.
     *
     * @return array
     */
    public function getShortcuts()
    {
        return $this->shortcuts;
    }

    /**
     * Get the alias the shortcut points to.
     *
     * @param string $name Twig function name.
     *
     * @return string Either the alias or the name passed in if not found.
     */
    public function getShortcut($name)
    {
        $key = strtolower($name);

        return (array_key_exists($key, $this->shortcuts)) ? $this->shortcuts[$key] : $name;
    }

    /**
     * Gets the class and method from the function name.
     *
     * For the AliasLoader to handle the undefined function, the function must
     * be in the format class_method(). This function checks whether the function
     * meets that format.
     *
     * @param string $name Function name.
     *
     * @return array|bool Array containing class and method or FALSE if incorrect format.
     */
    public function getAliasParts($name)
    {
        if (strpos($name, '_') !== false) {

            $parts = explode('_', $name);
            $parts = array_filter($parts); // Remove empty elements

            if (count($parts) < 2) {
                return false;
            }

            return [
                $parts[0],
                implode('_', array_slice($parts, 1))
            ];
        }

        return false;
    }

    /**
     * Looks in the lookup cache for the function.
     *
     * Repeat calls to an undefined function are cached.
     *
     * @param string $name Function name.
     *
     * @return Twig_Function_Function|false
     */
    public function getLookup($name)
    {
        $name = strtolower($name);

        return (array_key_exists($name, $this->lookup)) ? $this->lookup[$name] : false;
    }

    /**
     * Add undefined function to the cache.
     *
     * @param string                 $name     Function name.
     * @param Twig_Function_Function $function Function to cache.
     *
     * @return void
     */
    public function setLookup($name, Twig_Function_Function $function)
    {
        $this->lookup[strtolower($name)] = $function;
    }

    /**
     * Allow Twig to call Aliased function from an undefined function.
     *
     * @param string $name Undefined function name.
     *
     * @return Twig_Function_Function|false
     */
    public function getFunction($name)
    {
        $name = $this->getShortcut($name);

        // Check if we have looked this alias up before
        if ($function = $this->getLookup($name)) {
            return $function;
        }

        // Get the class / method we are trying to call
        $parts = $this->getAliasParts($name);

        if ($parts === false) {
            return false;
        }

        list($class, $method) = $parts;
        $class = strtolower($class);

        // Does that alias exist
        if (array_key_exists($class, $this->aliases)) {

            $class    = $this->aliases[$class];
            $function = new Twig_Function_Function($class.'::'.$method);

            $this->setLookup($name, $function);

            return $function;
        }

        return false;
    }
}
