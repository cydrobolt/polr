<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatController extends Controller
{
    /**
     * @param string $linkId
     * @return RedirectResponse|View
     */
    public function listClicks($linkId)
    {
        if (!$link = Link::find($linkId))
        {
            return redirect(route('admin'))->with('error', "Such link does not exist.");
        }

        if ($check = $this->performAuthCheck($link))
        {
            return $check;
        }

        $clicks = Click::where('link_id', $link->id)->orderBy('date', 'desc')->paginate(15);

        return view('clicks', ['link' => $link, 'clicks' => $clicks]);
    }

    /**
     * @param string $type
     * @param string $linkId
     * @return RedirectResponse|View
     */
    public function makeReport($type, $linkId)
    {
        if (!in_array($type,['day', 'country', 'referer']))
        {
            return redirect(route('admin'))->with('error', "Such a report does not exist.");
        }

        if (!$link = Link::find($linkId))
        {
            return redirect(route('admin'))->with('error', "Such link does not exist.");
        }

        if ($check = $this->performAuthCheck($link))
        {
            return $check;
        }

        switch ($type) {
            case 'day':
                $stats = DB::table('clicks')
                    ->select(DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as date, count(*) as clicks"))
                    ->where('link_id', $link->id)
                    ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m-%d')"))
                    ->orderBy('date', 'asc')
                    ->get();
                break;

            case 'country':
                $stats = DB::table('clicks')
                    ->select(DB::raw("country as label, count(*) as clicks"))
                    ->where('link_id', $link->id)
                    ->groupBy('country')
                    ->orderBy('clicks', 'desc')
                    ->get();
                break;

            case 'referer':
                $stats = DB::table('clicks')
                    ->select(DB::raw("COALESCE(referer_host, 'Direct') as label, count(*) as clicks"))
                    ->where('link_id', $link->id)
                    ->groupBy('referer_host')
                    ->orderBy('clicks', 'desc')
                    ->get();
                break;
        }

        return view('stats', ['link' => $link, 'stats' => $stats, 'type' => $type]);
    }

    /**
     * @param Link $link
     * @return RedirectResponse
     */
    private function performAuthCheck($link)
    {
        if (!self::isLoggedIn()) {
            return redirect(route('login'))->with('error', 'Please login to access your dashboard.');
        }

        $username = session('username');

        if (!$this->currIsAdmin() && $link->creator != $username)
        {
            return redirect(route('admin'))->with('error', "You don't have access to those link stats.");
        }
    }
}