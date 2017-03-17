<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiController extends Controller {
    protected static function encodeResponse($result, $action, $response_type='json', $plain_text_response=false) {
        $response = [
            "action" => $action,
            "result" => $result
        ];

        if ($response_type == 'json') {
            return response(json_encode($response))
                ->header('Content-Type', 'application/json')
                ->header('Access-Control-Allow-Origin', '*');
        }
        else {
            if ($plain_text_response) {
                // return alternative plain text response if provided
                $result = $plain_text_response;
            }
            // assume plain text if json not requested
            return response($result)
                ->header('Content-Type', 'text/plain')
                ->header('Access-Control-Allow-Origin', '*');
        }
    }
}
