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

        if (!env('SETTING_PUBLIC_INTERFACE') && !self::isLoggedIn()) {
            if (env('SETTING_INDEX_REDIRECT')) {
                return redirect()->to(env('SETTING_INDEX_REDIRECT'));
            }
            else {
                return redirect()->to(route('login'));
            }
        }

        return view('index', ['large' => true]);
    }
}
