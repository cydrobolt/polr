<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiHelper;

class ApiController extends ApiController {
    protected static function getApiUserInfo(Request $request) {
        $api_key = $request->input('api_key');
        $user = User::where('active', 1)
            ->where('api_key', $api_key)
            ->where('api_active', true)
            ->first();

        $api_limited_reached = ApiHelper::checkUserApiQuota($user->username);
    }

    protected static function encodeResponse($result, $action, $response_type='json') {
        $response = {
            "action" => $action,
            "result" => $result
        }

        if ($response_type == 'json') {
            return json_encode($response);
        }
        else if ($response_type == 'plain_text') {
            return $result;
        }
    }
}
