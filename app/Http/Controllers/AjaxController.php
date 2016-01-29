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
        if (!$this->currIsAdmin()) {
            abort(401, 'User not admin.');
        }

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
        if (!$this->currIsAdmin()) {
            abort(401, 'User not admin.');
        }

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id);

        if (!$user) {
            abort(404, 'User not found.');
        }

        $new_api_key = CryptoHelper::generateRandomHex(15);
        $user->api_key = $new_api_key;
        $user->save();

        return $user->api_key;
    }

    public function deleteUser(Request $request) {
        if (!$this->currIsAdmin()) {
            abort(401, 'User not admin.');
        }

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id);

        if (!$user) {
            abort(404, 'User not found.');
        }
        $user->delete();
        return "OK";
    }
}
