<?php


namespace App\Http\Middleware;

use Laravel\Lumen\Http\Middleware\VerifyCsrfToken as BaseVerifier;

/**
 * Class VerifyCsrfToken
 *
 * @package App\Http\Middleware
 *
 * @see    https://laravel.com/docs/5.1/routing#csrf-protection
 *
 * @author Andreas Sgouros <andreassgouros@gmail.com>
 */
class VerifyCsrfToken extends BaseVerifier
{
	/**
	 * The URIs that should be excluded from CSRF verification.
	 *
	 * @var array
	 */
	protected $except = [
		'api/v2/action*',
	];

	public function handle($request, \Closure $next)
	{
		if ($this->isReading($request)
			|| $this->shouldPassThrough($request)
			|| $this->tokensMatch($request)
		) {
			return $this->addCookieToResponse($request, $next($request));
		}

		throw new TokenMismatchException;
	}

	/**
	 * Determine if the request has a URI that should pass through CSRF verification.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function shouldPassThrough($request)
	{
		foreach ($this->except as $except) {
			if ($except !== '/') {
				$except = trim($except, '/');
			}
			if ($request->is($except)) {
				return true;
			}
		}
		return false;
	}
}