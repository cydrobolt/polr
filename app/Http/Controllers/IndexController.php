<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    /**
     * Show the index page.
     *
     * @return Response
     */
    public function showIndexPage() {
        return view('index', []);

        // return view('user.profile', ['user' => User::findOrFail($id)]);
    }
}
