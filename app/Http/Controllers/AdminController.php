<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class IndexController extends Controller {
    /**
     * Show the admin panel, and process admin AJAX requests.
     *
     * @return Response
     */
    public function displayAdminPage(Request $request) {
        return view('admin');
    }
}
