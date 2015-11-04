<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class StaticPageController extends Controller {
    /**
     * Show static pages such as the about page.
     *
     * @return Response
     */
    public function displayAbout(Request $request) {
        $user_role = session('role');
        return view('about', ['role' => $user_role, 'no_div_padding' => true]);
    }
}
