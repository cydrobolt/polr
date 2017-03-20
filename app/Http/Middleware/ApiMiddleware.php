<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\ApiHelper;
use App\Exceptions\Api\ApiException;

class ApiMiddleware {
    protected static function getApiUserInfo(Request $request) {
        $api_key = $request->input('key');
        $response_type = $request->input('response_type');

        if (!$api_key) {
            // no API key provided; check whether anonymous API is enabled

            if (env('SETTING_ANON_API')) {
                $username = 'ANONIP-' . $request->ip();
            }
            else {
                throw new ApiException('AUTH_ERROR', 'Authentication token required.', 401, $response_type);
            }
            $user = (object) [
                'username' => $username
            ];
        }
        else {
            $user = User::where('active', 1)
                ->where('api_key', $api_key)
                ->where('api_active', 1)
                ->first();

            if (!$user) {
                throw new ApiException('AUTH_ERROR', 'Authentication token required.', 401, $response_type);
            }
            $username = $user->username;
        }

        $api_limit_reached = ApiHelper::checkUserApiQuota($username);

        if ($api_limit_reached) {
            throw new ApiException('QUOTA_EXCEEDED', 'Quota exceeded.', 429, $response_type);
        }
        return $user;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next) {
        $request->user = $this->getApiUserInfo($request);

        return $next($request);
    }
}
