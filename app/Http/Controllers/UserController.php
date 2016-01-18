<?php
namespace App\Http\Controllers;
use Mail;
use App\Models\User;
use Illuminate\Http\Request;
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
        $ip = $request->ip();

        $hashed_password = Hash::make($password);

        $user_exists = UserHelper::userExists($username);
        $email_exists = UserHelper::emailExists($email);

        if ($user_exists || $email_exists) {
            // if user or email email
            return redirect('signup')->with('error', 'Sorry, your email or username already exists. Try again.');
        }

        $email_valid = UserHelper::validateEmail($email);

        if ($email_valid == false) {
            return redirect('signup')->with('error', 'Please use a valid email to sign up.');
        }

        $user = UserFactory::createUser($username, $email, $password, $active, $ip);

        $acct_activation_needed = env('POLR_ACCT_ACTIVATION');

        if ($acct_activation_needed == false) {
            // if no activation is necessary
            $user->active = 1;
            $response = redirect('login')->with('success', 'Thanks for signing up! You may now log in.');
        }
        else {
            // email activation is necessary
            Mail::send('emails.activation', [
                'username' => $username, 'recovery_key' => $user->recovery_key, 'ip' => $ip
            ], function ($m) use ($user) {
                    $m->to($user->email, $user->username)->subject(env('APP_NAME') . ' account activation');
            });
            $response = redirect('login')->with('success', 'Thanks for signing up! Please confirm your email to continue..');

        }

        return $response;
    }

    public static function performActivation(Request $request, $username, $recovery_key) {
        // TODO process activation
    }

}
