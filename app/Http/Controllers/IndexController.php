<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class IndexController extends Controller {
    /**
     * Show the index page.
     *
     * @return Response
     */
    public function showIndexPage(Request $request) {
        // $request->session()->put('username', "cydrobolt");
        return view('index', ['large' => true]);
    }
}
