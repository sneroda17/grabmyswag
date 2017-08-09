<?php

namespace App\Http\Middleware;

use App\Mailers\AppMailer;
use Closure;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next )
    {
        if (!\Auth::check()) {
            return redirect('login');
        }

        $user = \Auth::user();
        if ($user->phone) {
            if(!$user->verified)
                return redirect()->route('user-show-verify');
        }
        if($user->email)
        {
            if(!$user->confirmed)
            {
                \Auth::logout();
                \Request::session()->flash(
                    'error',
                    'You must confirm your eamil. Please check your message box!'
                );
                return redirect(url('/'));
            }
        }
        return $next($request);
    }
}
