<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->has('key') && !env('SETTING_ANON_API')) {
            abort(401, "Authentication token required.");
        }

        $user = User::where('active', 1)
            ->where('api_key', $request->get('key'))
            ->where('api_active', 1)
            ->first();

        $isAnonymousUser = empty($user) && env('SETTING_ANON_API');
        if (!$user && !$isAnonymousUser) {
            abort(401, "Invalid authentication token.");
        }

        $username = $isAnonymousUser ? 'ANONIP-' . $request->ip() : null;
        if ($user->hasReachedApiQuota($username)) {
            abort(403, "Quota exceeded.");
        }

        return $next($request);
    }
}
