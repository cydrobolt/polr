<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Link;

class LinkController extends Controller {
    /**
     * Show the admin panel, and process admin AJAX requests.
     *
     * @return Response
     */

    private function checkIfExists($link_ending) {
        /**
         * Provided a link ending (string),
         * check whether the ending is in use.
         * @return boolean
         */

    }

    public function performShorten(Request $request) {
        $long_url = $request->input('link-url');
        $custom_ending = $request->input('custom-ending');

        $is_secret = ($request->input('options') == "s" ? true : false);

        // TODO check if long_url is already a shortened URL

        if ($custom_ending) {
            // TODO check if custom ending is alphanum (maybe dashes)
        }

        if ($is_secret) {
            // TODO if secret label as custom and don't return on lookup
        }

        // TODO check if ending is in use
            // if not custom
                // TODO find the next base val available
            // if custom
                // TODO return an error

        $link_ending = "aaa";

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
                'error' => 'Sorry, but this link has been disabled by an administrator.'
            ]);
        }

        return redirect()->url($short_url);
    }
}
