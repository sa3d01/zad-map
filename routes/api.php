<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'prefix' => 'v1',
], function () {
    // General
    Route::group(['namespace' => 'General', 'prefix' => 'general'], function () {
        Route::get('cities', 'DropDownController@cities');
        Route::get('cities/{cityId}/districts', 'DropDownController@districts');
    });
    // AUTH
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
        Route::post('register', 'RegisterController@register');
        Route::post('resend-phone-verification', 'VerifyController@resendPhoneVerification');
        Route::post('verify-phone', 'VerifyController@verifyPhone');
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LoginController@logout')->middleware('auth:api');
    });
});
