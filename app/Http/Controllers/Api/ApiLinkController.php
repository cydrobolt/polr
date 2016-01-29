<?php
namespace App\Http\Controllers\Api;

use App\Factories\LinkFactory;
use App\Helpers\LinkHelper;

class ApiLinkController extends ApiController {
    public static function shortenLink(Request $request) {
        $response_type = $request->input('response_type');
        $ard = self::getApiUserInfo($request);

        /* */
        $long_url = $request->input('url');
        $is_secret = $request->input('is_secret');
        $custom_ending = $request->input('custom_ending');

        $formatted_link = LinkFactory::createLink();

        return self::encodeResponse($formatted_link, 'shorten', $response_type);
    }

    public static function lookupLink(Request $request) {
        $response_type = $request->input('response_type');
        $ard = self::getApiUserInfo($request);

        /* */
        $url_ending = $request->input('url_ending');
        $link_or_false = LinkHelper::linkExists($url_ending);

        if ($link_or_false) {
            return $link_or_false;
        }
        else {
            abort(404, "Link not found.");
        }

    }
}
