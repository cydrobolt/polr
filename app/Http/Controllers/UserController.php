<?php
namespace App\Http\Controllers;
use Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     * Show pages related to the user control panel.
     *
     * @return Response
     */
    public function displayLoginPage(Request $request) {
        return view('login');
    }

    public function performLogin(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $hashed_password = Hash::make($password);

        $user = User::where('active', 1)
            ->where('username', $username)
            ->where('password', $hashed_password)
            ->first();

        if ($user == null){
            // ok
        }
        else {
            return view('login', [
                'error' => "Invalid password or inactivated account. Try again."
            ]);
        }


    }

    public function logoutUser(Request $request) {
        $request->session()->forget('username');
        return redirect()->route('index');
    }
}
