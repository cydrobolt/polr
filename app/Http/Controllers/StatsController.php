<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;
use Carbon\Carbon;

use App\Models\Link;
use App\Models\Clicks;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller {
    const DAYS_TO_FETCH = 30;

    private function getBaseRows($link_id) {
        // Get past month rows
        return DB::table('clicks')
            ->where('link_id', $link_id)
            ->where('created_at', '>=', Carbon::now()->subDays(self::DAYS_TO_FETCH));
    }

    private function getDayStats($link_id) {
        // Return stats by day from the last 30 days
        // date => x
        // clicks => y
        $stats = $this->getBaseRows($link_id)
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') AS x, count(*) AS y"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->orderBy('x', 'asc')
            ->get();

        return $stats;
    }

    private function getCountryStats($link_id) {
        $stats = $this->getBaseRows($link_id)
            ->select(DB::raw("country AS label, count(*) AS clicks"))
            ->groupBy('country')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }

    private function getRefererStats($link_id) {
        $stats = $this->getBaseRows($link_id)
            ->select(DB::raw("COALESCE(referer_host, 'Direct') as label, count(*) as clicks"))
            ->groupBy('referer_host')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }


    public function displayStats(Request $request, $short_url) {
        if (!$this->isLoggedIn()) {
            return redirect(route('login'))->with('error', 'Please login to view link stats.');
        }

        $link = Link::where('short_url', $short_url)
            ->first();

        // Return 404 if link not found
        if ($link == null) {
            return redirect(route('admin'))->with('error', 'Cannot show stats for nonexistent link.');
        }
        if (!env('SETTING_ADV_ANALYTICS')) {
            return redirect(route('login'))->with('error', 'Please enable advanced analytics to view this page.');
        }

        $link_id = $link->id;

        if ( (session('username') != $link->creator) && !self::currIsAdmin() ) {
            return redirect(route('admin'))->with('error', 'You do not have permission to view stats for this link.');
        }

        $day_stats = $this->getDayStats($link_id);
        $country_stats = $this->getCountryStats($link_id);
        $referer_stats = $this->getRefererStats($link_id);

        return view('link_stats', [
            'link' => $link,
            'day_stats' => $day_stats,
            'country_stats' => $country_stats,
            'referer_stats' => $referer_stats,

            'no_div_padding' => true
        ]);
    }
}
