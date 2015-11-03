<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge\Extension\Loader\Facade;

use Twig_Markup;

/**
 * Handles calling the method on the called facade.
 */
class Caller
{
    /**
     * @var string The name of the facade that has to be called
     */
    protected $facade;

    /**
     * @var array Customisation options for the called facade / method.
     */
    protected $options;

    /**
     * Create a new caller for a facade.
     *
     * @param string $facade
     * @param array  $options
     */
    public function __construct($facade, array $options = [])
    {
        $this->facade  = $facade;
        $this->options = array_merge(
            [
                'is_safe' => null,
                'charset' => null,
            ],
            $options
        );
    }

    /**
     * Return facade that will be called.
     *
     * @return string
     */
    public function getFacade()
    {
        return $this->facade;
    }

    /**
     * Return extension options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Call the method on the facade.
     *
     * Supports marking the method as safe, i.e. the returned HTML won't be escaped.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        $is_safe = ($this->options['is_safe'] === true);

        // Allow is_safe option to specify individual methods of the facade that are safe
        if (is_array($this->options['is_safe']) && in_array($method, $this->options['is_safe'])) {
            $is_safe = true;
        }

        $result  = forward_static_call_array([$this->facade, $method], $arguments);
        $is_safe = ($is_safe && (is_string($result) || method_exists($result, '__toString')));

        return ($is_safe) ? new Twig_Markup($result, $this->options['charset']) : $result;
    }
}
