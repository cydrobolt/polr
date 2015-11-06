<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Redirect;

use App\Models\Link;

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

        if ($is_secret) {
            // TODO if secret label as custom and don't return on lookup
        }

        if ($custom_ending) {
            // has custom ending
            $is_alphanum = ctype_alnum($custom_ending);

            if (!$is_alphanum) {
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
        $link->is_custom = isset($custom_ending);

        if ($creator) {
            // if user is logged in, save user as creator
            $link->creator = $creator;
        }

        $link->save();

        $short_url = env('APP_PROTOCOL') . env('APP_ADDRESS') . "/" . $link_ending;
        return view('shorten_result', ['short_url' => $short_url]);
    }

    public function performRedirect(Request $request, $short_url) {
        $link = Link::where('short_url', $short_url)
            ->first();

        if ($link == null) {
            return abort(404);
        }

        if ($link['disabled'] == 1) {
            return view('error', [
                'message' => 'Sorry, but this link has been disabled by an administrator.'
            ]);
        }

        $long_url = $link->long_url;
        return redirect()->to($long_url);
    }
}
