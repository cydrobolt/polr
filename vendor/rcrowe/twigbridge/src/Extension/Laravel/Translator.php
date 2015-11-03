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
use Twig_SimpleFilter;
use Twig_SimpleFunction;
use Illuminate\Translation\Translator as LaravelTranslator;

/**
 * Access Laravels translator class in your Twig templates.
 */
class Translator extends Twig_Extension
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * Create a new translator extension
     *
     * @param \Illuminate\Translation\Translator
     */
    public function __construct(LaravelTranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TwigBridge_Extension_Laravel_Translator';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('trans', [$this->translator, 'trans']),
            new Twig_SimpleFunction('trans_choice', [$this->translator, 'transChoice']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter(
                'trans',
                [$this->translator, 'trans'],
                [
                    'pre_escape' => 'html',
                    'is_safe'    => ['html'],
                ]
            ),
            new Twig_SimpleFilter(
                'trans_choice',
                [$this->translator, 'transChoice'],
                [
                    'pre_escape' => 'html',
                    'is_safe'    => ['html'],
                ]
            ),
        ];
    }
}
