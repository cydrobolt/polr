<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Helpers\LinkHelper;
use App\Helpers\UserHelper;
use App\Helpers\StatsHelper;
use App\Exceptions\Api\ApiException;

class ApiAnalyticsController extends ApiController {
    public function lookupLinkStats (Request $request, $stats_type=false) {
        $user = $request->user;
        $response_type = $request->input('response_type') ?: 'json';

        if ($response_type != 'json') {
            throw new ApiException('JSON_ONLY', 'Only JSON-encoded data is available for this endpoint.', 401, $response_type);
        }

        $validator = \Validator::make($request->all(), [
            'url_ending' => 'required|alpha_dash',
            'stats_type' => 'alpha_num',
            'left_bound' => 'date',
            'right_bound' => 'date'
        ]);

        if ($validator->fails()) {
            throw new ApiException('MISSING_PARAMETERS', 'Invalid or missing parameters.', 400, $response_type);
        }

        $url_ending = $request->input('url_ending');
        $stats_type = $request->input('stats_type');
        $left_bound = $request->input('left_bound');
        $right_bound = $request->input('right_bound');
        $stats_type = $request->input('stats_type');

        // ensure user can only read own analytics or user is admin
        $link = LinkHelper::linkExists($url_ending);

        if ($link === false) {
            throw new ApiException('NOT_FOUND', 'Link not found.', 404, $response_type);
        }

        if (($link->creator != $user->username) &&
                !(UserHelper::userIsAdmin($user->username))){
            // If user does not own link and is not an admin
            throw new ApiException('ACCESS_DENIED', 'Unauthorized.', 401, $response_type);
        }

        try {
            $stats = new StatsHelper($link->id, $left_bound, $right_bound);
        }
        catch (\Exception $e) {
            throw new ApiException('ANALYTICS_ERROR', $e->getMessage(), 400, $response_type);
        }

        if ($stats_type == 'day') {
            $fetched_stats = $stats->getDayStats();
        }
        else if ($stats_type == 'country') {
            $fetched_stats = $stats->getCountryStats();
        }
        else if ($stats_type == 'referer') {
            $fetched_stats = $stats->getRefererStats();
        }
        else {
            throw new ApiException('INVALID_ANALYTICS_TYPE', 'Invalid analytics type requested.', 400, $response_type);
        }

        return self::encodeResponse([
            'url_ending' => $link->short_url,
            'data' => $fetched_stats,
        ], 'data_link_' . $stats_type, $response_type, false);
    }
}
