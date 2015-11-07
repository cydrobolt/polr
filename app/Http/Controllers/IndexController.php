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
        $random_key = CryptoHelper::generateRandomHex(50);
        return view('index', ['large' => true]);
    }
}
