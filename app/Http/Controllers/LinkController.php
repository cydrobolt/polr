<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;

use App\Models\Link;

use App\Helpers\CryptoHelper;
use App\Helpers\LinkHelper;

class LinkController extends Controller {
    /**
     * Show the admin panel, and process admin AJAX requests.
     *
     * @return Response
     */

    private function renderError($message) {
        $this->request->session()->flash('error', $message);

        return redirect()->route('index');
    }

    private function formatAndRender($link_ending, $secret_ending=False) {
        $short_url = env('APP_PROTOCOL') . env('APP_ADDRESS') . '/' . $link_ending;
        if ($secret_ending) {
            $short_url .= '/' . $secret_ending;
        }
        return view('shorten_result', ['short_url' => $short_url]);
    }


    public function performShorten(Request $request) {
        $this->request = $request;

        $long_url = $request->input('link-url');
        $custom_ending = $request->input('custom-ending');
        $is_secret = ($request->input('options') == "s" ? true : false);
        $creator = session('username');

        $is_already_short = LinkHelper::checkIfAlreadyShortened($long_url);
        if ($is_already_short) {
            return $this->renderError('Sorry, but your link already
                looks like a shortened URL.');
        }

        if (!$is_secret && $existing_link = LinkHelper::longLinkExists($long_url)) {
            // if link is not specified as secret, is non-custom, and
            // already exists in Polr, lookup the value and return
            return $this->formatAndRender($existing_link);
        }

        if ($custom_ending) {
            // has custom ending
            $ending_conforms = LinkHelper::validateEnding($custom_ending);
            if (!$ending_conforms) {
                return $this->renderError('Sorry, but custom endings
                    can only contain alphanumeric characters');
            }

            $ending_in_use = LinkHelper::linkExists($custom_ending);
            if ($ending_in_use) {
                return $this->renderError('Sorry, but this URL ending is already in use.');
            }

            $link_ending = $custom_ending;
        }
        else {
            // no custom ending
            $link_ending = LinkHelper::findSuitableEnding();
        }

        $link = new Link;
        $link->short_url = $link_ending;
        $link->long_url  = $long_url;
        $link->ip        = $request->ip();
        $link->is_custom = $custom_ending != null;

        if ($creator) {
            // if user is logged in, save user as creator
            $link->creator = $creator;
        }

        if ($is_secret) {
            $rand_bytes_num = intval(env('POLR_SECRET_BYTES'));
            $secret_key = CryptoHelper::generateRandomHex($rand_bytes_num);
            $link->secret_key = $secret_key;
        }
        else {
            $secret_key = false;
        }

        $link->save();

        return $this->formatAndRender($link_ending, $secret_key);
    }

    public function performRedirect(Request $request, $short_url, $secret_key=false) {
        $link = Link::where('short_url', $short_url)
            ->first();

        if ($link == null) {
            return abort(404);
        }

        $link_secret_key = $link->secret_key;

        if ($link->disabled == 1) {
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
        return redirect()->to($long_url);
    }
}
