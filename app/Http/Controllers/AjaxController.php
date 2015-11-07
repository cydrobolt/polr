<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\LinkHelper;

class AjaxController extends Controller {
    /**
     * Process non-admin AJAX requests.
     *
     * @return Response
     */
    public function checkLinkAvailability(Request $request) {
        $link_ending = $request->input('link_ending');
        $ending_conforms = LinkHelper::validateEnding($link_ending);

        if (!$ending_conforms) {
            return "invalid";
        }
        else if (LinkHelper::linkExists($link_ending)) {
            // if ending already exists
            return "unavailable";
        }
        else {
            return "available";
        }
    }
}
