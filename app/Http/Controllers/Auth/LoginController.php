<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mailers\AppMailer;
use App\SocialAccount;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected function validateLogin(Request $request)
    {
        if(is_numeric($request->email))
        {
            $this->validate($request, [
                $this->username() => 'required|phone',
                'password' => 'required|string',
            ]);
        }
        else
        {
            $this->validate($request, [
                $this->username() => 'required|email',
                'password' => 'required|string',
            ]);
        }
    }

    public function login(Request $request, AppMailer $mailer)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $email = $request->email;
        $password = $request->password;
        if(is_numeric($email))
        {
            if (Auth::attempt(['phone' => $email, 'password' => $password])) {
                // Authentication passed...
                \Log::info('logged');
                return redirect(url('/profile'));
            }
        }
        else
        {
            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                // Authentication passed...
                $user = Auth::user();

                if (!$user->confirmed) {
                    $mailer->sendEmailConfirmationTo($user);

                    Auth::logout();
                    \Session::flush();

                    \Request::session()->flash(
                        'status',
                        'Please check your message box!'
                    );

                    return redirect('login');
                }
                return redirect(url('/profile'));
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function get_countries()
    {
        return Countries::all();
    }
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Socialite
     */

    public function redirectToProvider($provider)
    {
        return \Socialite::driver($provider)->redirect();
    }

    public function addProvider($provider)
    {
        \Session::set("social-add-{$provider}", "1");
        return \Socialite::driver($provider)->redirect();
    }

    /**
     * This method is called in callback when user returns from a social network
     * authentication procedure.
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $social = $this->getSocialAccount(\Socialite::driver($provider)->user(), $provider);

        if ($user = $social->user) {
            \Auth::login($user);
            // Device ID will be associated with user in $this->authenticate
            return redirect(url('/profile'));
        }

        $user = $this->createUserFromSocial($social, $social->email);
        \Auth::login($user);

        $social->save();

        return redirect(url('/profile'));
    }

    /**
     * Fetches from DB or creates a new social account record
     * for the specified social user.
     *
     * @param \Laravel\Socialite\Contracts\User $userData
     * @param string $provider
     * @return SocialAccount
     */
    private function getSocialAccount(\Laravel\Socialite\Contracts\User $userData, $provider)
    {
        $social = SocialAccount::where([
            'provider' => $provider,
            'provider_id' => $userData->getId(),
        ])->first();

        if (!$social) {
            $social = new SocialAccount();
            $social->provider = $provider;
            $social->provider_id = $userData->getId();
        }

        $social->username = $userData->getNickname() ?: $userData->getName();
        $social->email = $userData->getEmail();
        $social->data = json_encode($userData);

        return $social;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = new User([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => isset($data['password']) ? bcrypt($data['password']) : null,
        ]);

        if (isset($data['confirmed'])) {
            $user->confirmed = 1;
        }
        $user->save();

        return $user;
    }

    /**
     * This method creates a new social account but ONLY if the email address is provided
     * and is currently not in use.
     *
     * @param SocialAccount $account
     * @param string|null $email
     * @return User|null
     */
    private function createUserFromSocial(SocialAccount $account, $email)
    {
        if (!$email || User::where('email', $email)->count()) {
            return null;
        }

        $user = $this->create([
            'name' => $account->username,
            'email' => $email,
            'confirmed' => true
        ]);
        $user->socialAccounts()->save($account);

        return $user;
    }
}
