<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;

use App\Models\Link;
use App\Factories\LinkFactory;
use App\Helpers\CryptoHelper;
use App\Helpers\LinkHelper;

class LinkController extends Controller {
    /**
     * Show the admin panel, and process admin AJAX requests.
     *
     * @return Response
     */

    private function renderError($message) {
        return redirect(route('index'))->with('error', $message);
    }

    public function performShorten(Request $request) {
        if (env('SETTING_SHORTEN_PERMISSION') && !self::isLoggedIn()) {
            return redirect(route('index'))->with('error', 'You must be logged in to shorten links.');
        }

        $this->request = $request;

        $long_url = $request->input('link-url');
        $custom_ending = $request->input('custom-ending');
        $is_secret = ($request->input('options') == "s" ? true : false);

        $creator = session('username');

        $link_ip = $request->ip();

        try {
            $short_url = LinkFactory::createLink($long_url, $is_secret, $custom_ending, $link_ip, $creator);
        }
        catch (\Exception $e) {
            return self::renderError($e->getMessage());
        }

        return view('shorten_result', ['short_url' => $short_url]);
    }

    public function performRedirect(Request $request, $short_url, $secret_key=false) {
        $link = Link::where('short_url', $short_url)
            ->first();

        if ($link == null) {
            return abort(404);
        }

        $link_secret_key = $link->secret_key;

        if ($link->is_disabled == 1) {
            return view('error', [
                'message' => 'Sorry, but this link has been disabled by an administrator.'
            ]);
        }

        if ($link_secret_key) {
            if (!$secret_key) {
                // if we do not receieve a secret key
                // when we are expecting one, return a 404
                return abort(404);
            }
            else {
                if ($link_secret_key != $secret_key) {
                    // a secret key is provided, but it is incorrect
                    return abort(404);
                }
            }

        }

        $long_url = $link->long_url;
        $clicks = intval($link->clicks);

        if (is_int($clicks)) {
            $clicks += 1;
        }

        $link->clicks = $clicks;

        $link->save();

        LinkHelper::processPostClick($link);

        return redirect()->to($long_url);
    }
}
