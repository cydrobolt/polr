<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;

use App\Models\Link;
use App\Models\Clicks;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller {
    private function getDayStats($link_id) {
        // date => x
        // clicks => y
        $stats = DB::table('clicks')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as x, count(*) as y"))
            ->where('link_id', $link_id)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->orderBy('x', 'asc')
            ->get();

        return $stats;
    }

    private function getCountryStats($link_id) {
        $stats = DB::table('clicks')
            ->select(DB::raw("country as label, count(*) as clicks"))
            ->where('link_id', $link_id)
            ->groupBy('country')
            ->orderBy('clicks', 'desc')
            ->get();

        return $stats;
    }

    private function getRefererStats($link_id) {
        $stats = DB::table('clicks')
            ->select(DB::raw("COALESCE(referer_host, 'Direct') as label, count(*) as clicks"))
            ->where('link_id', $link_id)
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
            'referer_stats' => $referer_stats
        ]);
    }
}
