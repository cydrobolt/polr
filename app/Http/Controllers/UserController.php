<?php
namespace App\Http\Controllers;
use Mail;
use App\Models\User;
use Illuminate\Http\Request;

use App\Helpers\CryptoHelper;
use App\Helpers\UserHelper;

use App\Factories\UserFactory;

class UserController extends Controller {
    /**
     * Show pages related to the user control panel.
     *
     * @return Response
     */
    public function displayLoginPage(Request $request) {
        return view('login');
    }

    public function displaySignupPage(Request $request) {
        return view('signup');
    }

    public function logoutUser(Request $request) {
        $request->session()->forget('username');
        return redirect()->route('index');
    }

    public function performLogin(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $credentials_valid = UserHelper::checkCredentials($username, $password);

        if ($credentials_valid != false) {
            // log user in
            $role = $credentials_valid['role'];
            $request->session()->put('username', $username);
            $request->session()->put('role', $role);

            return redirect()->route('index');
        }
        else {
            return redirect('login')->with('error', 'Invalid password or inactivated account. Try again.');
        }
    }

    public function performSignup(Request $request) {
        if (env('POLR_ALLOW_ACCT_CREATION') == false) {
            return redirect(route('index'))->with('error', 'Sorry, but registration is disabled.');
        }

        $username = $request->input('username');
        $password = $request->input('password');
        $email = $request->input('email');

        if (!self::checkRequiredArgs([$username, $password, $email])) {
            // missing a required argument
            return redirect(route('signup'))->with('error', 'Please fill in all required fields.');
        }

        $ip = $request->ip();

        $user_exists = UserHelper::userExists($username);
        $email_exists = UserHelper::emailExists($email);

        if ($user_exists || $email_exists) {
            // if user or email email
            return redirect(route('signup'))->with('error', 'Sorry, your email or username already exists. Try again.');
        }

        $email_valid = UserHelper::validateEmail($email);

        if ($email_valid == false) {
            return redirect(route('signup'))->with('error', 'Please use a valid email to sign up.');
        }

        $acct_activation_needed = env('POLR_ACCT_ACTIVATION');

        if ($acct_activation_needed == false) {
            // if no activation is necessary
            $active = 1;
            $response = redirect(route('login'))->with('success', 'Thanks for signing up! You may now log in.');
        }
        else {
            // email activation is necessary
            $response = redirect(route('login'))->with('success', 'Thanks for signing up! Please confirm your email to continue..');
            $active = 0;
        }

        $api_active = false;
        $api_key = null;
        if (env('SETTING_AUTO_API') == 'on') {
            // if automatic API key assignment is on
            $api_active = 1;
            $api_key = CryptoHelper::generateRandomHex(env('_API_KEY_LENGTH'));
        }


        $user = UserFactory::createUser($username, $email, $password, $active, $ip, $api_key, $api_active);

        if ($acct_activation_needed) {
            Mail::send('emails.activation', [
                'username' => $username, 'recovery_key' => $user->recovery_key, 'ip' => $ip
            ], function ($m) use ($user) {
                $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                $m->to($email, $username)->subject(env('APP_NAME') . ' account activation');
            });
        }

        return $response;
    }

    public static function performActivation(Request $request, $username, $recovery_key) {
        // TODO process activation
    }

}
