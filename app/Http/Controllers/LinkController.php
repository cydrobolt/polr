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
    public function performRedirect(Request $request, $short_url) {
        $link = Link::where('short_url', $short_url)
            ->first();

        if ($link == null) {
            return abort(404);
        }

        if ($link['disabled'] == 1) {
            return 'oops disabled sry';
        }

    }
}
