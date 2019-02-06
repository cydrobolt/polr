<?php
namespace App\Http\Controllers;
use Mail;
use Hash;
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

    public function displayLostPasswordPage(Request $request) {
        return view('lost_password');
    }

    public function performLogoutUser(Request $request) {
        $request->session()->forget('username');
        $request->session()->forget('role');
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
            return redirect('login')->with('error', __('controller.user.invalidauth'));
        }
    }

    public function performSignup(Request $request) {
        if (env('POLR_ALLOW_ACCT_CREATION') == false) {
            return redirect(route('index'))->with('error', __('controller.user.registerdisabled'));
        }

        if (env('POLR_ACCT_CREATION_RECAPTCHA')) {
            // Verify reCAPTCHA if setting is enabled
            $gRecaptchaResponse = $request->input('g-recaptcha-response');

            $recaptcha = new \ReCaptcha\ReCaptcha(env('POLR_RECAPTCHA_SECRET_KEY'));
            $recaptcha_resp = $recaptcha->verify($gRecaptchaResponse, $request->ip());

            if (!$recaptcha_resp->isSuccess()) {
                return redirect(route('signup'))->with('error', __('controller.user.wrongcaptcha'));
            }
        }

        // Validate signup form data
        $this->validate($request, [
            'username' => 'required|alpha_dash',
            'password' => 'required',
            'email' => 'required|email'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $email = $request->input('email');

        if (env('SETTING_RESTRICT_EMAIL_DOMAIN')) {
            $email_domain = explode('@', $email)[1];
            $permitted_email_domains = explode(',', env('SETTING_ALLOWED_EMAIL_DOMAINS'));

            if (!in_array($email_domain, $permitted_email_domains)) {
                return redirect(route('signup'))->with('error', __('controller.user.forbiddenemail'));
            }
        }

        $ip = $request->ip();

        $user_exists = UserHelper::userExists($username);
        $email_exists = UserHelper::emailExists($email);

        if ($user_exists || $email_exists) {
            // if user or email email
            return redirect(route('signup'))->with('error', __('controller.user.takenemail'));
        }

        $acct_activation_needed = env('POLR_ACCT_ACTIVATION');

        if ($acct_activation_needed == false) {
            // if no activation is necessary
            $active = 1;
            $response = redirect(route('login'))->with('success', __('controller.user.registered'));
        }
        else {
            // email activation is necessary
            $response = redirect(route('login'))->with('success', __('controller.user.registeredemail'));
            $active = 0;
        }

        $api_active = false;
        $api_key = null;

        if (env('SETTING_AUTO_API')) {
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

                $m->to($user->email, $user->username)->subject(__('controller.user.mailsubject', ['app' => env('APP_NAME')]));
            });
        }

        return $response;
    }

    public function performSendPasswordResetCode(Request $request) {
        if (!env('SETTING_PASSWORD_RECOV')) {
            return redirect(route('index'))->with('error', __('controller.user.nopasswdrecover'));
        }

        $email = $request->input('email');
        $ip = $request->ip();
        $user = UserHelper::getUserByEmail($email);

        if (!$user) {
            return redirect(route('lost_password'))->with('error', __('controller.user.emailnotexists'));
        }

        $recovery_key = UserHelper::resetRecoveryKey($user->username);

        Mail::send('emails.lost_password', [
            'username' => $user->username, 'recovery_key' => $recovery_key, 'ip' => $ip
        ], function ($m) use ($user) {
            $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

            $m->to($user->email, $user->username)->subject(__('controller.user.passwdmailsubject', ['app' => env('APP_NAME')]));
        });

        return redirect(route('index'))->with('success', __('controller.user.recoverpasswd'));
    }

    public function performActivation(Request $request, $username, $recovery_key) {
        $user = UserHelper::getUserByUsername($username, true);

        if (UserHelper::userResetKeyCorrect($username, $recovery_key, true)) {
            // Key is correct
            // Activate account and reset recovery key
            $user->active = 1;
            $user->save();

            UserHelper::resetRecoveryKey($username);
            return redirect(route('login'))->with('success', __('controller.user.activated'));
        }
        else {
            return redirect(route('index'))->with('error', __('controller.user.incorrect'));
        }
    }

    public function performPasswordReset(Request $request, $username, $recovery_key) {
        $new_password = $request->input('new_password');
        $user = UserHelper::getUserByUsername($username);

        if (UserHelper::userResetKeyCorrect($username, $recovery_key)) {
            if (!$new_password) {
                return view('reset_password');
            }

            // Key is correct
            // Reset password
            $user->password = Hash::make($new_password);
            $user->save();

            UserHelper::resetRecoveryKey($username);
            return redirect(route('login'))->with('success', __('controller.user.passrecover'));
        }
        else {
            return redirect(route('index'))->with('error', __('controller.user.passrecoverfail'));
        }

    }

}
