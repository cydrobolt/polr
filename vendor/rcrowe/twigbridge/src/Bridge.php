<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge;

use Twig_Environment;
use Twig_LoaderInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\ViewFinderInterface;
use InvalidArgumentException;
use Twig_Error;

/**
 * Bridge functions between Laravel & Twig
 */
class Bridge extends Twig_Environment
{
    /**
     * @var string TwigBridge version
     */
    const BRIDGE_VERSION = '0.8.0';

    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    public function __construct(Twig_LoaderInterface $loader, $options = [], Application $app = null)
    {
        // Twig 2.0 doesn't support `true` anymore
        if (isset($options['autoescape']) && $options['autoescape'] === true) {
            $options['autoescape'] = 'html';
        }
        
        parent::__construct($loader, $options);

        $this->app = $app;
    }

    /**
     * Get the Laravel app.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Set the Laravel app.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    public function loadTemplate($name, $index = null)
    {
        $template = parent::loadTemplate($name, $index);

        $template->setName($this->normalizeName($name));

        return $template;
    }

    /**
     * Lint (check) the syntax of a file on the view paths.
     *
     * @param string $file File to check. Supports dot-syntax.
     *
     * @return bool Whether the file passed or not.
     */
    public function lint($file)
    {
        $template = $this->app['twig.loader.viewfinder']->getSource($file);

        if (!$template) {
            throw new InvalidArgumentException('Unable to find file: '.$file);
        }

        try {
            $this->parse($this->tokenize($template, $file));
        } catch (Twig_Error $e) {
            return false;
        }

        return true;
    }

    /**
     * Merges a context with the shared variables, same as mergeGlobals()
     *
     * @param array $context An array representing the context
     *
     * @return array The context merged with the globals
     */
    public function mergeShared(array $context)
    {
        // we don't use array_merge as the context being generally
        // bigger than globals, this code is faster.
        foreach ($this->app['view']->getShared() as $key => $value) {
            if (!array_key_exists($key, $context)) {
                $context[$key] = $value;
            }
        }

        return $context;
    }

    /**
     * Normalize a view name.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function normalizeName($name)
    {
        $extension = '.' . $this->app['twig.extension'];
        $length = strlen($extension);

        if (substr($name, -$length, $length) === $extension) {
            $name = substr($name, 0, -$length);
        }

        // Normalize namespace and delimiters
        $delimiter = ViewFinderInterface::HINT_PATH_DELIMITER;
        if (strpos($name, $delimiter) === false) {
            return str_replace('/', '.', $name);
        }

        list($namespace, $name) = explode($delimiter, $name);

        return $namespace.$delimiter.str_replace('/', '.', $name);
    }
}
