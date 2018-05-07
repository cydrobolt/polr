<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Factories\LinkFactory;
use App\Helpers\LinkHelper;
use App\Exceptions\Api\ApiException;

class ApiLinkController extends ApiController {
    protected function getShortenedLink($long_url, $is_secret, $custom_ending, $link_ip, $username, $response_type) {
        try {
            $formatted_link = LinkFactory::createLink(
                $long_url, $is_secret, $custom_ending, $link_ip, $username, false, true);
        }
        catch (\Exception $e) {
            throw new ApiException('CREATION_ERROR', $e->getMessage(), 400, $response_type);
        }

        return $formatted_link;
    }

    public function shortenLink(Request $request) {
        $response_type = $request->input('response_type');
        $user = $request->user;

        $validator = \Validator::make(array_merge([
            'url' => str_replace(' ', '%20', $request->input('url'))
        ], $request->except('url')), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $formatted_link = $this->getShortenedLink(
            $request->input('url'),
            ($request->input('is_secret') == 'true' ? true : false),
            $request->input('custom_ending'),
            $request->ip(),
            $user->username,
            $response_type
        );

        return self::encodeResponse($formatted_link, 'shorten', $response_type);
    }

    public function shortenLinksBulk(Request $request) {
        $response_type = $request->input('response_type', 'json');
        $request_data = $request->input('data');

        $user = $request->user;
        $link_ip = $request->ip();
        $username = $user->username;

        if ($response_type != 'json') {
            throw new ApiException('JSON_ONLY', 'Only JSON-encoded responses are available for this endpoint.', 401, $response_type);
        }

        $links_array_raw_json = json_decode($request_data, true);

        if ($links_array_raw_json === null) {
            throw new ApiException('INVALID_PARAMETERS', 'Invalid JSON.', 400, $response_type);
        }

        $links_array = $links_array_raw_json['links'];

        foreach ($links_array as $link) {
            $validator = \Validator::make($link, [
                'url' => 'required|url'
            ]);

            if ($validator->fails()) {
                throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
            }
        }

        $formatted_links = [];

        foreach ($links_array as $link) {
            $formatted_link = $this->getShortenedLink(
                $link['url'],
                (array_get($link, 'is_secret') == 'true' ? true : false),
                array_get($link, 'custom_ending'),
                $link_ip,
                $username,
                $response_type
            );

            $formatted_links[] = [
                'long_url' => $link['url'],
                'short_url' => $formatted_link
            ];
        }

        return self::encodeResponse([
            'shortened_links' => $formatted_links
        ], 'shorten_bulk', 'json');
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
