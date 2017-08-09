<?php

namespace App\Http\Controllers;

use App\Cities;
use App\Countries;
use App\Http\Middleware\CustomAuth;
use App\Providers\AuthyApiProvider;
use App\State;
use Authy\AuthyApi;
use GuzzleHttp\Client;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

use App\Http\Requests;


class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    protected function validator(array $data)
    {
        if(is_numeric($data['email']))
        {
            return Validator::make($data, [
                'name' => 'required|string|max:255',
                'country' => 'required',
                'age' => 'required|numeric',
                'zip' => 'required|numeric'
            ]);
        }
        else
        {
            return Validator::make($data, [
                'name' => 'required|string|max:255',

                'country' => 'required',
                'age' => 'required|numeric',
                'zip' => 'required|numeric'
            ]);
        }
    }

    public function showProfile()
    {
        $user = Auth::user();

        if ($user->verified || $user->confirmed)
        {
            $countries = Countries::all();
  //          return;
            return view('profile_show')->with('user',$user)->with('countries',$countries);
        }
    }

    public function editProfile()
    {
        $user = Auth::user();

        if ($user->verified || $user->confirmed)
        {
            $countries = Countries::all();
            //          return;
            return view('profile')->with('user',$user)->with('countries',$countries);
        }
    }

    public function update(Request $request)
    {
        $data = $request::all();
        $this->validator($data)->validate();
        $user = \Auth::user();
        if(is_numeric($data['email']))
        {
            $user->fill([
                'name' => $data['name'],
                'phone' => $data['email'],
                'zip' => $data['zip'],
                'country' => $data['country'],
                'city' => $data['city'],
                'age' => $data['age'],
                'category' => $data['feedback'],
                'education' => $data['education']
            ]);
        }
        else
        {
            $user->fill([
                'name' => $data['name'],
                'email' => $data['email'],
                'zip' => $data['zip'],
                'country' => $data['country'],
                'city' => $data['city'],
                'age' => $data['age'],
                'category' => $data['feedback'],
                'education' => $data['education']
            ]);
        }
        $user->save();

        return redirect('profile');
    }

    public function getState()
    {
        $country = Input::get('country_id');

        $country_id = Countries::where('name', $country)->first();

        $states = State::where('country_id',$country_id['id'])->get();
        $plucked = $states->pluck('id');
        $cities = array();
        foreach ($plucked as $state)
        {
            \Log::info($state);
            $city = Cities::where('state_id',$state)->get();
            $plucked = $city->pluck('name');
            foreach ($plucked as $pluck)
            {
                array_push($cities, $pluck);
            }
        }
        return $cities;
    }

    public function getCountry()
    {
        $country = Countries::all();
        $plucked = $country->pluck('name');
        $cities = array();
        foreach($plucked as $pluck)
            array_push($cities, $pluck);
        return $cities;
    }

    /**
     * This controller function shows the current user status
     *
     * @param Authenticatable $user Current user
     * @return mixed Response view
     */
    /**
     * This controller function handles the submission form
     *
     * @param \Request $request Current User Request
     * @param AuthyApi $authyApi Authy Client
     * @return mixed Response view
     */
    public function verify( AuthyApi $authyApi)
    {
        $user = Auth::user();
        $token = Request::input('token');
        $verification = $authyApi->verifyToken($user->authy_id, $token);
        if ($verification->ok()) {
            $user->verified = true;
            $user->save();
//            $this->sendSmsNotification($client, $user);
            return redirect(url('/profile'));
        } else {
            $errors = $this->getAuthyErrors($verification->errors());
            return view('verifyUser', ['errors' => new MessageBag($errors)]);
        }
    }
    /**
     * This controller function handles the submission form
     *
     * @param \Request $request Current User Request
     * @param AuthyApi $authyApi Authy Client
     * @return mixed Response view
     */
    public function verifyResend(AuthyApi $authyApi)
    {
        $user = \Auth::user();
//        if($user)
            \Log::info('resend');
            \Log::info($user->authy_id);
            $sms = $authyApi->requestSms($user->authy_id);

            if ($sms->ok()) {
                \Request::session()->flash(
                    'status',
                    'Verification code re-sent'
                );
                return redirect()->route('user-show-verify');
            } else {
                $errors = $this->getAuthyErrors($sms->errors());
                return view('verifyUser', ['errors' => new MessageBag($errors)]);
            }
    }

    private function getAuthyErrors($authyErrors)
    {
        $errors = [];
        foreach ($authyErrors as $field => $message) {
            array_push($errors, $field . ': ' . $message);
        }
        return $errors;
    }

    private function sendSmsNotification($client, $user)
    {
        $twilioNumber = config('services.twilio')['number'] or die(
        "TWILIO_NUMBER is not set in the environment"
        );
        $messageBody = 'You did it! Signup complete :)';

        $client->messages->create(
            $user->fullNumber(),    // Phone number which receives the message
            [
                "from" => $twilioNumber, // From a Twilio number in your account
                "body" => $messageBody
            ]
        );
    }
}
