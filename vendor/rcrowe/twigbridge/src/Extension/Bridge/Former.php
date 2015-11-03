<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigBridge\Extension\Bridge;

use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Use Former in your Twig templates.
 *
 * @see https://github.com/formers/former
 */
class Former extends Twig_Extension
{
	/**
	 * @var \Former\Former
	 */
	protected $former;

	/**
	 * Create a new Former extension.
	 *
	 * @param  \Former\Former
	 */
	public function __construct(\Former\Former $former)
	{
		$this->former = $former;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'TwigBridge_Extension_Bridge_Former';
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFunctions()
	{
		return [
		  new Twig_SimpleFunction(
			'former_*',
			function ($name) {
				$arguments = array_slice(func_get_args(), 1);
				return call_user_func_array([$this->former, $name], $arguments);
			}, ['is_safe' => ['html']]
		  ),
		];
	}
}
