<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('home');
});
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/auth/{provider}', 'Auth\LoginController@redirectToProvider')->name('auth.forward');
Route::get('/auth/{provider}/add', 'Auth\LoginController@addProvider')->name('auth.add_provider');
Route::get('/auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('auth.callback');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
Route::post('/register', 'Auth\RegisterController@register');

Route::post('/user/verify', 'ProfileController@verify')->name('user-verify');
Route::get(
    '/user/verify', ['as' => 'user-show-verify', function() {
        return response()->view('verifyUser');
    }]
);

Route::group(['middleware' => 'profile'], function(){
    Route::post('/getallcountry','ProfileController@getCountry');
    Route::post('/getcountry_pro',[
        'uses' => 'ProfileController@getState'
    ]);
    Route::post('/getstate_pro',[
        'uses' => 'ProfileController@getCity'
    ]);

    Route::get('/profile', 'ProfileController@showProfile');

    Route::get('/editprofile', 'ProfileController@editProfile');

    Route::post('/update','ProfileController@update');

});

Route::post('/user/verify/resend', 'ProfileController@verifyResend')->name('user-verify-resend');



Route::post('/getcountry',[
    'uses' => 'Auth\RegisterController@getState'
]);


Route::post('/getstate',[
    'uses' => 'Auth\RegisterController@getCity'
]);


Route::get('register/confirm/{token}', 'Auth\RegisterController@confirmEmail');