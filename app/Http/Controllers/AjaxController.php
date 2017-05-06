<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LinkHelper;
use App\Helpers\CryptoHelper;
use App\Helpers\UserHelper;
use App\Models\User;
use App\Factories\UserFactory;

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
        /**
         * If user is an admin, allow resetting of any API key
         *
         * If user is not an admin, allow resetting of own key only, and only if
         * API is enabled for the account.
         * @return string; new API key
         */


        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id);

        $username_user_requesting = session('username');
        $user_requesting = UserHelper::getUserByUsername($username_user_requesting);

        if (!$user) {
            abort(404, 'User not found.');
        }

        if ($user != $user_requesting) {
            // if user is attempting to reset another user's API key,
            // ensure they are an admin
            self::ensureAdmin();
        }
        else {
            // user is attempting to reset own key
            // ensure that user is permitted to access the API
            $user_api_enabled = $user->api_active;
            if (!$user_api_enabled) {
                // if the user does not have API access toggled on,
                // allow only if user is an admin
                self::ensureAdmin();
            }
        }

        $new_api_key = CryptoHelper::generateRandomHex(env('_API_KEY_LENGTH'));
        $user->api_key = $new_api_key;
        $user->save();

        return $user->api_key;
    }

    public function editAPIQuota(Request $request) {
        /**
         * If user is an admin, allow the user to edit the per minute API quota of
         * any user.
         */

        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $new_quota = $request->input('new_quota');
        $user = UserHelper::getUserById($user_id);

        if (!$user) {
            abort(404, 'User not found.');
        }
        $user->api_quota = $new_quota;
        $user->save();
        return "OK";
    }

    public function toggleUserActive(Request $request) {
        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id, true);

        if (!$user) {
            abort(404, 'User not found.');
        }
        $current_status = $user->active;

        if ($current_status == 1) {
            $new_status = 0;
        }
        else {
            $new_status = 1;
        }

        $user->active = $new_status;
        $user->save();

        return $user->active;
    }

    public function changeUserRole(Request $request) {
        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $role = $request->input('role');
        $user = UserHelper::getUserById($user_id, true);

        if (!$user) {
            abort(404, 'User not found.');
        }

        $user->role = $role;
        $user->save();

        return "OK";
    }

    public function addNewUser(Request $request) {
        self::ensureAdmin();

        $ip = $request->ip();
        $username = $request->input('username');
        $user_password = $request->input('user_password');
        $user_email = $request->input('user_email');
        $user_role = $request->input('user_role');

        UserFactory::createUser($username, $user_email, $user_password, 1, $ip, false, 0, $user_role);

        return "OK";
    }

    public function deleteUser(Request $request) {
        self::ensureAdmin();

        $user_id = $request->input('user_id');
        $user = UserHelper::getUserById($user_id, true);

        if (!$user) {
            abort(404, 'User not found.');
        }

        $user->delete();
        return "OK";
    }

    public function deleteLink(Request $request) {
        self::ensureAdmin();

        $link_ending = $request->input('link_ending');
        $link = LinkHelper::linkExists($link_ending);

        if (!$link) {
            abort(404, 'Link not found.');
        }

        $link->delete();
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

    public function editLinkLongUrl(Request $request) {
        /**
         * If user is an admin, allow the user to edit the value of any link's long URL.
         * Otherwise, only allow the user to edit their own links.
         */

        $link_ending = $request->input('link_ending');
        $link = LinkHelper::linkExists($link_ending);

        $new_long_url = $request->input('new_long_url');

        $this->validate($request, [
            'new_long_url' => 'required|url',
        ]);

        if (!$link) {
            abort(404, 'Link not found.');
        }

        if ($link->creator !== session('username')) {
            self::ensureAdmin();
        }

        $link->long_url = $new_long_url;
        $link->save();
        return "OK";
    }
}
