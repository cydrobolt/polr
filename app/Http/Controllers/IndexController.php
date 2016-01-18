<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\CryptoHelper;

class IndexController extends Controller {
    /**
     * Show the index page.
     *
     * @return Response
     */
    public function showIndexPage(Request $request) {
        if (env('POLR_SETUP_RAN') != true) {
            return redirect(route('setup'));
        }
        return view('index', ['large' => true]);
    }
}
