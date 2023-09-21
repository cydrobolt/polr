<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;

use App\Models\Link;
use App\Factories\LinkFactory;
use App\Helpers\CryptoHelper;
use App\Helpers\LinkHelper;
use App\Helpers\ClickHelper;

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

        // Validate URL form data
        $this->validate($request, [
            'link-url' => 'required|url',
            'custom-ending' => 'alpha_dash'
        ]);

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

        // Return 404 if link not found
        if ($link == null) {
        	return abort(404);
        }

        // Return an error if the link has been disabled
        // or return a 404 if SETTING_REDIRECT_404 is set to true
        if ($link->is_disabled == 1) {
            if (env('SETTING_REDIRECT_404')) {
                return abort(404);
            }

            return view('error', [
                'message' => 'Sorry, but this link has been disabled by an administrator.'
            ]);
        }

        // Return a 403 if the secret key is incorrect
        $link_secret_key = $link->secret_key;
        if ($link_secret_key) {
        	if (!$secret_key) {
        		// if we do not receive a secret key
        		// when we are expecting one, return a 403
        		return abort(403);
        	}
        	else {
        		if ($link_secret_key != $secret_key) {
        			// a secret key is provided, but it is incorrect
        			return abort(403);
        		}
        	}
        }

        // Increment click count
        $long_url = $link->long_url;
        $clicks = intval($link->clicks);

        if (is_int($clicks)) {
            $clicks += 1;
        }
        $link->clicks = $clicks;
        $link->save();

        if (env('SETTING_ADV_ANALYTICS')) {
            // Record advanced analytics if option is enabled
            ClickHelper::recordClick($link, $request);
        }
        // Redirect to final destination
        return redirect()->to($long_url, 301);
    }

}
