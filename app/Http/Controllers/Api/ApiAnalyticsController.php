<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

// use App\Factories\LinkFactory;
use App\Helpers\LinkHelper;
use App\Helpers\StatsHelper;

class ApiLinkController extends ApiController {
    public function lookupLinkAnalytics (Request $request) {
        $response_type = $request->input('response_type');
        $user = self::getApiUserInfo($request);

    }
}
