<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\LinkHelper;
use App\Helpers\UserHelper;
use App\Helpers\StatsHelper;
use App\Exceptions\Api\ApiException;

class ApiAnalyticsController extends ApiController {

    public function lookupLinkStats(Request $request, $stats_type = false) {
        $user = $request->user;
        $response_type = $request->input('response_type') ? : 'json';

        if ($user->anonymous) {
            throw new ApiException('AUTH_ERROR', 'Anonymous access of this API is not permitted.', 401, $response_type);
        }

        if ($response_type != 'json') {
            throw new ApiException('JSON_ONLY', 'Only JSON-encoded data is available for this endpoint.', 400, $response_type);
        }

        $validator = \Validator::make($request->all(), [
                    'url_ending' => 'required|alpha_dash',
                    'stats_type' => 'required',
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

        $stats_type_arr = explode(",", $stats_type);

        // ensure user can only read own analytics or user is admin
        $link = LinkHelper::linkExists($url_ending);

        if ($link === false) {
            throw new ApiException('NOT_FOUND', 'Link not found.', 404, $response_type);
        }

        if (($link->creator != $user->username) &&
                !(UserHelper::userIsAdmin($user->username))) {
            // If user does not own link and is not an admin
            throw new ApiException('ACCESS_DENIED', 'Unauthorized.', 401, $response_type);
        }

        try {
            $stats = new StatsHelper($link->id, $left_bound, $right_bound);
        } catch (\Exception $e) {
            throw new ApiException('ANALYTICS_ERROR', $e->getMessage(), 400, $response_type);
        }

        $fetched_stats = [];

        if (in_array('day', $stats_type_arr)) {
            if (count($stats_type_arr) == 1) {
                $fetched_stats = $stats->getDayStats();
            } else {
                $fetched_stats['day'] = $stats->getDayStats();
            }
        }
        if (in_array('country', $stats_type_arr)) {
            if (count($stats_type_arr) == 1) {
                $fetched_stats = $stats->getCountryStats();
            } else {
                $fetched_stats['country'] = $stats->getCountryStats();
            }
        }
        if (in_array('referer', $stats_type_arr)) {
            if (count($stats_type_arr) == 1) {
                $fetched_stats = $stats->getRefererStats();
            } else {
                $fetched_stats['referer'] = $stats->getRefererStats();
            }
        }


        return self::encodeResponse([
                    'url_ending' => $link->short_url,
                    'data' => $fetched_stats,
                        ], 'data_link_' . $stats_type, $response_type, false);
    }

}
