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

use Illuminate\Support\Debug\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * Dump a variable or the view context
 *
 * Based on the Symfony Twig Bridge Dump Extension
 * @see https://github.com/symfony/symfony/blob/2.6/src/Symfony/Bridge/Twig/Extension/DumpExtension.php
 * @author Nicolas Grekas <p@tchwork.com>
 */
class Dump extends \Twig_Extension
{
    public function __construct()
    {
        $this->cloner = new VarCloner();
    }

    public function getFunctions()
    {
        return array(
          new \Twig_SimpleFunction('dump', array($this, 'dump'), array('is_safe' => array('html'), 'needs_context' => true, 'needs_environment' => true)),
        );
    }

    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Dump';
    }

    public function dump(\Twig_Environment $env, $context)
    {
        if (!$env->isDebug()) {
            return;
        }
        if (2 === func_num_args()) {
            $vars = array();
            foreach ($context as $key => $value) {
                if (!$value instanceof \Twig_Template) {
                    $vars[$key] = $value;
                }
            }
            $vars = array($vars);
        } else {
            $vars = func_get_args();
            unset($vars[0], $vars[1]);
        }
        $dump = fopen('php://memory', 'r+b');
        $dumper = new HtmlDumper($dump);
        foreach ($vars as $value) {
            $dumper->dump($this->cloner->cloneVar($value));
        }
        rewind($dump);
        return stream_get_contents($dump);
    }
}
