<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Helpers\LinkHelper;
use App\Helpers\UserHelper;
use App\Helpers\StatsHelper;

class ApiAnalyticsController extends ApiController {
    public function lookupLinkStats (Request $request, $stats_type=false) {
        $response_type = $request->input('response_type') ?: 'json';

        if ($response_type != 'json') {
            abort(401, 'Only JSON-encoded data is available for this endpoint.');
        }

        $user = self::getApiUserInfo($request);

        $validator = \Validator::make($request->all(), [
            'url_ending' => 'required|alpha_dash',
            'stats_type' => 'alpha_num',
            'left_bound' => 'date',
            'right_bound' => 'date'
        ]);

        error_log($validator->errors());
        if ($validator->fails()) {
            return abort(400, 'Invalid or missing parameters.');
        }

        $url_ending = $request->input('url_ending');
        $stats_type = $request->input('stats_type');
        $left_bound = $request->input('left_bound');
        $right_bound = $request->input('right_bound');
        $stats_type = $request->input('stats_type');

        // ensure user can only read own analytics or user is admin
        $link = LinkHelper::linkExists($url_ending);

        if ($link === false) {
            abort(404, 'Link not found.');
        }

        if (($link->creator != $user->username) &&
                !(UserHelper::userIsAdmin($username))){
            // If user does not own link and is not an admin
            abort(401, 'You do not have access to this link.');
        }

        $stats = new StatsHelper($link->id, $left_bound, $right_bound);

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
            abort(400, 'Invalid analytics type requested.');
        }

        return self::encodeResponse([
            'url_ending' => $link->short_url,
            'data' => $fetched_stats,
        ], 'data_link_' . $stats_type, $response_type, false);
    }
}
