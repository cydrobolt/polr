<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

use App\Models\Link;
use App\Models\User;
use App\Helpers\UserHelper;

class AdminPaginationController extends Controller {
    /**
     * Process AJAX Datatables pagination queries from the admin panel.
     *
     * @return Response
     */

    public function paginateAdminUsers(Request $request) {
        self::ensureAdmin();

        $admin_users = User::select(['username', 'email', 'created_at', 'active', 'api_key', 'api_active', 'api_quota', 'role', 'id']);
        return Datatables::of($admin_users)
            ->addColumn('api_action', function ($user) {
                // Add "API Info" action button
                return '<a class="activate-api-modal btn btn-sm btn-info"
                    ng-click="openAPIModal($event, \'' . $user->username . '\', \'' . $user->api_key . '\', \'' . $user->api_active . '\', \'' . $user->api_quota . '\', \'' . $user->id . '\')">
                    API info
                </a>';
            })
            ->addColumn('toggle_active', function ($user) {
                // Add user account active state toggle buttons
                $btn_class = '';
                if (session('username') == $user->username) {
                    $btn_class = ' disabled';
                }

                if ($user->active) {
                    $active_text = 'Active';
                    $btn_color_class = ' btn-success';
                }
                else {
                    $active_text = 'Inactive';
                    $btn_color_class = ' btn-danger';
                }

                return '<a class="btn btn-sm status-display' . $btn_color_class . $btn_class . '" ng-click="toggleUserActiveStatus($event, ' . $user->id . ')">' . $active_text . '</a>';
            })
            ->addColumn('change_role', function ($user) {
                // Add "change role" select box
                // FIXME <select> field does not use Angular bindings
                // because of an issue affecting fields with duplicate names.

                $select_role = '<select ng-init="changeUserRole.u'.$user->id.' = \''.$user->role.'\'"
                    ng-model="changeUserRole.u'.$user->id.'" ng-change="changeUserRole(changeUserRole.u'.$user->id.', '.$user->id.')"
                    class="form-control"';

                if (session('username') == $user->username) {
                    // Do not allow user to change own role
                    $select_role .= ' disabled';
                }
                $select_role .= '>';

                foreach (UserHelper::USER_ROLES as $role_text => $role_val) {
                    // Iterate over each available role and output option
                    $select_role .= '<option value="' . $role_val . '"';

                    if ($user->role == $role_val) {
                        $select_role .= ' selected';
                    }

                    $select_role .= '>' . $role_text . '</option>';
                }

                $select_role .= '</select>';
                return $select_role;
            })
            ->addColumn('delete', function ($user) {
                // Add "Delete" action button
                $btn_class = '';
                if (session('username') == $user->username) {
                    $btn_class = 'disabled';
                }
                return '<a ng-click="deleteUser($event, \''. $user->id .'\')" class="btn btn-sm btn-danger ' . $btn_class . '">
                    Delete
                </a>';
            })
            ->make(true);
    }

    public function paginateAdminLinks(Request $request) {
        self::ensureAdmin();

        $admin_links = Link::select(['short_url', 'long_url', 'clicks', 'created_at', 'creator', 'is_disabled']);
        return Datatables::of($admin_links)
            ->addColumn('disable', function ($link) {
                // Add "Disable/Enable" action buttons
                $btn_class = 'btn-danger';
                $btn_text = 'Disable';

                if ($link->is_disabled) {
                    $btn_class = 'btn-success';
                    $btn_text = 'Enable';
                }

                return '<a ng-click="toggleLink($event, \'' . $link->short_url . '\')" class="btn btn-sm ' . $btn_class . '">
                    ' . $btn_text . '
                </a>';
            })
            ->addColumn('delete', function ($link) {
                // Add "Delete" action button
                return '<a ng-click="deleteLink($event, \'' . $link->short_url  . '\')"
                    class="btn btn-sm btn-warning delete-link">
                    Delete
                </a>';
            })
            ->make(true);
    }

    public function paginateUserLinks(Request $request) {
        self::ensureLoggedIn();

        $username = session('username');
        $user_links = Link::where('creator', $username)
            ->select(['short_url', 'long_url', 'clicks', 'created_at']);

        return Datatables::of($user_links)
            ->make(true);
    }
}
