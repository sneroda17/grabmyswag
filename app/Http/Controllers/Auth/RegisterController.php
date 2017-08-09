<?php

namespace App\Http\Controllers\Auth;

use App\Mailers\AppMailer;
use App\Cities;
use App\Countries;
use App\State;
use App\User;
use App\Http\Controllers\Controller;
use Authy\AuthyApi;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\MessageBag;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if(is_numeric($data['email']))
        {
            return Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|max:11|unique:users|phone',
                'password' => 'required|string|min:6',
                'country' => 'required',
                'age' => 'required|numeric',
                'zip' => 'required|numeric'
            ]);
        }
        else
        {
            return Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|max:255|unique:users|email',
                'password' => 'required|string|min:6',
                'country' => 'required',
                'age' => 'required|numeric',
                'zip' => 'required|numeric'
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if(is_numeric($data['email']) )
        {
            return User::create([
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'zip' => $data['zip'],
                'country' => $data['country'],
                'city' => $data['city'],
                'age' => $data['age'],
                'category' => $data['feedback'],
                'education' => $data['education'],
                'phone' => $data['email']
            ]);
        }
        else
        {
            return User::create([
                'name' => $data['name'],
                'password' => bcrypt($data['password']),
                'zip' => $data['zip'],
                'country' => $data['country'],
                'city' => $data['city'],
                'age' => $data['age'],
                'category' => $data['feedback'],
                'education' => $data['education'],
                'email' => $data['email']
            ]);
        }

    }

    public function showRegistrationForm()
    {
        $countries = Countries::all();
        return view('auth.register')->with('countries', $countries);
    }

    public function register(AuthyApi $authyApi , AppMailer $mailer)
    {

        $data = \Request::all();
        $this->validator($data)->validate();

        DB::beginTransaction();

        event(new Registered($user = $this->create($data)));

        $this->guard()->login($user);
        DB::commit();

        $email = $user->email;
        $phone = $user->phone;
         if(is_numeric($phone))
         {
             $countrycode = $this->getCountryCode($phone);
             $phone = substr($phone,strlen($countrycode)-1);
            $authyUser = $authyApi->registerUser(
                'newuser@yandex.ru',
                $phone,
                $countrycode
            );
            if ($authyUser->ok()) {
                $user->authy_id = $authyUser->id();
                $user->save();

                $authyApi->requestSms($user->authy_id);

                return redirect()->route('user-show-verify');
            } else {

                $errors = $this->getAuthyErrors($authyUser->errors());
//                DB::rollback();
                $countries = Countries::all();
                return view('auth.register', ['errors' => new MessageBag($errors)])->with('countries', $countries);
            }
        }
        else
        {
            $mailer->sendEmailConfirmationTo($user);
            \Request::session()->flash(
                'status',
                'Please check your message box!'
            );
            return redirect(url('/'));
        }
    }



    public function getState()
    {
        $country = Input::get('country_id');
        $states = State::where('country_id',$country)->get();
        $cities = array();
        foreach ($states as $state)
        {
            $city = Cities::where('state_id',$state->id)->get();
            $plucked = $city->pluck('name');
            foreach ($plucked as $pluck)
            {
                array_push($cities,$pluck);
                \Log::info($pluck);
            }
        }
        return $cities;
    }

    private function getAuthyErrors($authyErrors)
    {
        $errors = [];
        foreach ($authyErrors as $field => $message) {
            array_push($errors, $field . ': ' . $message);
        }
        return $errors;
    }

    public function confirmEmail($token)
    {

        $user = User::where('token', $token)->first();

        if($user)
        {
            $user->confirmed = true;
            $user->token = null;
            $user->save();
        }

        \Request::session()->flash(
            'status',
            'Your email is verified! Please Login.'
        );
        return redirect('profile');
    }

    public function getCountryCode($phone)
    {
        $countrycode = substr($phone, 0,4);
        $countries = Countries::all();
        $plucked = $countries->pluck('phonecode');
        $countries = $plucked->all();
        for ($i = 4; $i > 0; $i--)
        {
            $countrycode = substr($phone, 0,$i);
            foreach ($countries as $country)
            {
                if($countrycode == $country)
                {
                    return $country;
                }
            }
        }
    }
}
