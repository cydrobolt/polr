<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\User;

class AdminController extends Controller {
    /**
     * Show the admin panel, and process admin AJAX requests.
     *
     * @return Response
     */
    public function displayAdminPage(Request $request) {
        if (!$this->isLoggedIn()) {
            return view('errors.404');
        }

        $username = session('username');
        $role = session('role');

        $admin_users = null;
        $admin_links = null;

        if ($this->currIsAdmin()) {
            $admin_users = User::paginate(15);
            $admin_links = Link::paginate(15);
        }

        $user_links = Link::where('creator', $username)
            ->paginate(15);

        return view('admin', [
            'role' => $role,
            'admin_users' => $admin_users,
            'admin_links' => $admin_links,
            'user_links' => $user_links
        ]);
    }
}
