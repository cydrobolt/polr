<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Factories\LinkFactory;
use App\Helpers\LinkHelper;
use App\Helpers\UserHelper;
use App\Models\Link;
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

    /**
     * Resolve the link for $url_ending and ensure the API user may manage it.
     * Ownership mirrors AjaxController::editLinkLongUrl: a user may manage their
     * own links; admins may manage any. Anonymous API users own nothing.
     *
     * @return \App\Models\Link
     */
    protected function getOwnedLink($url_ending, $user, $response_type) {
        if (!empty($user->anonymous)) {
            throw new ApiException('ACCESS_DENIED', 'Anonymous API users cannot manage links.', 401, $response_type);
        }

        $link = LinkHelper::linkExists($url_ending);
        if (!$link) {
            throw new ApiException('NOT_FOUND', 'Link not found.', 404, $response_type);
        }

        if ($link->creator !== $user->username && !UserHelper::userIsAdmin($user->username)) {
            throw new ApiException('ACCESS_DENIED', 'You do not have permission to manage this link.', 401, $response_type);
        }

        return $link;
    }

    public function listLinks(Request $request) {
        $user = $request->user;
        $response_type = $request->input('response_type');

        if (!empty($user->anonymous)) {
            throw new ApiException('ACCESS_DENIED', 'Anonymous API users cannot list links.', 401, $response_type);
        }

        $query = Link::orderBy('created_at', 'desc');

        // Non-admins may only see links they created.
        if (!UserHelper::userIsAdmin($user->username)) {
            $query = $query->where('creator', $user->username);
        }

        // Optional case-insensitive substring filter on slug or destination.
        $filter = $request->input('query');
        if ($filter !== null && $filter !== '') {
            $query = $query->where(function ($q) use ($filter) {
                $q->where('short_url', 'like', '%' . $filter . '%')
                  ->orWhere('long_url', 'like', '%' . $filter . '%');
            });
        }

        $links = [];
        foreach ($query->get() as $link) {
            $links[] = [
                'short_url'   => $link->short_url,
                'long_url'    => $link->long_url,
                'clicks'      => $link->clicks,
                'is_disabled' => (bool) $link->is_disabled,
                'is_secret'   => $link->secret_key ? true : false,
                'created_at'  => (string) $link->created_at,
            ];
        }

        return self::encodeResponse(['links' => $links], 'list', $response_type);
    }

    public function renameLink(Request $request) {
        $user = $request->user;
        $response_type = $request->input('response_type');

        $validator = \Validator::make($request->all(), [
            'url_ending' => 'required|alpha_dash',
            'new_ending' => 'required|alpha_dash',
        ]);
        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $old_ending = $request->input('url_ending');
        $new_ending = $request->input('new_ending');

        $link = $this->getOwnedLink($old_ending, $user, $response_type);

        if (!LinkHelper::validateEnding($new_ending)) {
            throw new ApiException('CREATION_ERROR',
                'Custom endings can only contain alphanumeric characters, hyphens, and underscores.', 400, $response_type);
        }
        if ($new_ending === $old_ending) {
            throw new ApiException('CREATION_ERROR', 'The new ending is identical to the current one.', 400, $response_type);
        }
        if (LinkHelper::linkExists($new_ending)) {
            throw new ApiException('CREATION_ERROR', 'This URL ending is already in use.', 400, $response_type);
        }

        $link->short_url = $new_ending;
        $link->is_custom = 1;
        $link->save();

        $short_url = env('APP_PROTOCOL') . env('APP_ADDRESS') . '/' . $new_ending;
        return self::encodeResponse([
            'old_ending' => $old_ending,
            'new_ending' => $new_ending,
            'short_url'  => $short_url,
            'long_url'   => $link->long_url,
        ], 'rename', $response_type, $short_url);
    }

    public function updateLink(Request $request) {
        $user = $request->user;
        $response_type = $request->input('response_type');

        $validator = \Validator::make(array_merge([
            'long_url' => str_replace(' ', '%20', $request->input('long_url'))
        ], $request->except('long_url')), [
            'url_ending' => 'required|alpha_dash',
            'long_url'   => 'required|url',
        ]);
        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $url_ending = $request->input('url_ending');
        $long_url = $request->input('long_url');

        $link = $this->getOwnedLink($url_ending, $user, $response_type);

        // setLongUrlAttribute recomputes the crc32 hash for us.
        $link->long_url = $long_url;
        $link->save();

        return self::encodeResponse([
            'short_url' => env('APP_PROTOCOL') . env('APP_ADDRESS') . '/' . $url_ending,
            'long_url'  => $link->long_url,
        ], 'update', $response_type, $link->long_url);
    }

    public function toggleLink(Request $request) {
        $user = $request->user;
        $response_type = $request->input('response_type');

        $validator = \Validator::make($request->all(), [
            'url_ending' => 'required|alpha_dash',
        ]);
        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $url_ending = $request->input('url_ending');
        $link = $this->getOwnedLink($url_ending, $user, $response_type);

        $link->is_disabled = $link->is_disabled ? 0 : 1;
        $link->save();

        return self::encodeResponse([
            'url_ending'  => $url_ending,
            'is_disabled' => (bool) $link->is_disabled,
        ], 'toggle', $response_type, $link->is_disabled ? 'disabled' : 'enabled');
    }

    public function deleteLink(Request $request) {
        $user = $request->user;
        $response_type = $request->input('response_type');

        $validator = \Validator::make($request->all(), [
            'url_ending' => 'required|alpha_dash',
        ]);
        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $url_ending = $request->input('url_ending');
        $link = $this->getOwnedLink($url_ending, $user, $response_type);
        $link->delete();

        return self::encodeResponse(['deleted' => $url_ending], 'delete', $response_type, 'OK');
    }
}
