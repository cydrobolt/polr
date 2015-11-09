<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AdminController extends Controller {
    /**
     * Show the admin panel, and process admin AJAX requests.
     *
     * @return Response
     */
    public function displayAdminPage(Request $request) {
        $role = session('role');

        return view('admin', [
            'role' => $role
        ]);
    }
}
