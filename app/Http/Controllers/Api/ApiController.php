<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Helpers\ApiHelper;

class ApiController extends Controller {
    protected static function getApiUserInfo(Request $request) {
        $api_key = $request->input('key');

        if (!$api_key) {
            // no API key provided -- check whether anonymous API is on

            if (env('SETTING_ANON_API')) {
                $username = 'ANONIP-' . $request->ip();
            }
            else {
                abort(401, "Authentication token required.");
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
                abort(401, "Invalid authentication token.");
            }
            $username = $user->username;
        }

        $api_limit_reached = ApiHelper::checkUserApiQuota($username);

        if ($api_limit_reached) {
            abort(403, "Quota exceeded.");
        }
        return $user;
    }

    protected static function encodeResponse($result, $action, $response_type='json', $plain_text_response=false) {
        $response = [
            "action" => $action,
            "result" => $result
        ];

        if ($response_type == 'json') {
            return response(json_encode($response))
                ->header('Content-Type', 'application/json');
        }
        else {
            if ($plain_text_response) {
                // return alternative plain text response if provided
                $result = $plain_text_response;
            }
            // assume plain text if json not requested
            return response($result)
                ->header('Content-Type', 'text/plain');
        }
    }

}
