<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LinkHelper;
use App\Helpers\CryptoHelper;
use App\Helpers\UserHelper;
use App\Models\User;

class AjaxController extends Controller {
    /**
     * Process AJAX requests.
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

    public function toggleAPIActive(Request $request) {
        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id);

        if (!$user) {
            abort(404, 'User not found.');
        }
        $current_status = $user->api_active;

        if ($current_status == 1) {
            $new_status = 0;
        }
        else {
            $new_status = 1;
        }

        $user->api_active = $new_status;
        $user->save();

        return $user->api_active;
    }

    public function generateNewAPIKey(Request $request) {
        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id);

        if (!$user) {
            abort(404, 'User not found.');
        }

        $new_api_key = CryptoHelper::generateRandomHex(env('_API_KEY_LENGTH'));
        $user->api_key = $new_api_key;
        $user->save();

        return $user->api_key;
    }

    public function deleteUser(Request $request) {
        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id);

        if (!$user) {
            abort(404, 'User not found.');
        }
        $user->delete();
        return "OK";
    }

    public function toggleLink(Request $request) {
        self::ensureAdmin();

        $link_ending = $request->input('link_ending');
        $link = LinkHelper::linkExists($link_ending);

        if (!$link) {
            abort(404, 'Link not found.');
        }

        $current_status = $link->is_disabled;

        $new_status = 1;

        if ($current_status == 1) {
            // if currently disabled, then enable
            $new_status = 0;
        }

        $link->is_disabled = $new_status;

        $link->save();

        return ($new_status ? "Enable" : "Disable");
    }
}
