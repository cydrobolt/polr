<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Factories\LinkFactory;
use App\Helpers\LinkHelper;
use App\Exceptions\Api\ApiException;

class ApiLinkController extends ApiController {
    public function shortenLink(Request $request) {
        $response_type = $request->input('response_type');
        $user = $request->user;

        // Validate parameters
        // Encode spaces as %20 to avoid validator conflicts
        $validator = \Validator::make(array_merge([
            'url' => str_replace(' ', '%20', $request->input('url'))
        ], $request->except('url')), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $long_url = $request->input('url'); // * required
        $is_secret = ($request->input('is_secret') == 'true' ? true : false);

        $link_ip = $request->ip();
        $custom_ending = $request->input('custom_ending');

        try {
            $formatted_link = LinkFactory::createLink($long_url, $is_secret, $custom_ending, $link_ip, $user->username, false, true);
        }
        catch (\Exception $e) {
            throw new ApiException('CREATION_ERROR', $e->getMessage(), 400, $response_type);
        }

        return self::encodeResponse($formatted_link, 'shorten', $response_type);
    }

    public function lookupLink(Request $request) {
        $user = $request->user;
        $response_type = $request->input('response_type');

        // Validate URL form data
        $validator = \Validator::make($request->all(), [
            'url_ending' => 'required|alpha_dash'
        ]);

        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $url_ending = $request->input('url_ending');

        // "secret" key required for lookups on secret URLs
        $url_key = $request->input('url_key');

        $link = LinkHelper::linkExists($url_ending);

        if ($link['secret_key']) {
            if ($url_key != $link['secret_key']) {
                throw new ApiException('ACCESS_DENIED', 'Invalid URL code for secret URL.', 401, $response_type);
            }
        }

        if ($link) {
            return self::encodeResponse([
                'long_url' => $link['long_url'],
                'created_at' => $link['created_at'],
                'clicks' => $link['clicks'],
                'updated_at' => $link['updated_at'],
                'created_at' => $link['created_at']
            ], 'lookup', $response_type, $link['long_url']);
        }
        else {
            throw new ApiException('NOT_FOUND', 'Link not found.', 404, $response_type);
        }
    }
}
