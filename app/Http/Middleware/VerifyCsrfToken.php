<?php

namespace App\Http\Middleware;
use Laravel\Lumen\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Exclude API routes from CSRF protection.
     *
     * @var array
     */
    public function handle($request, \Closure $next) {
        if ($request->is('api/v*/action/*') || $request->is('api/v*/data/*')) {
            // Exclude public API from CSRF protection
            // but do not exclude private API endpoints
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
