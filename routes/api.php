<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\JwtTokenIsValid;

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
        Route::get('settings', 'SettingController@getSettings');
        Route::get('cities', 'DropDownController@cities');
        Route::get('cities/{cityId}/districts', 'DropDownController@districts');
        Route::get('pages/{user_type}/{type}', 'PageController@getPage');
        Route::get('slider', 'SliderController@index');
        Route::get('category', 'CategoryController@index');
    });
    // AUTH
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
        Route::post('register', 'RegisterController@register');
        Route::post('resend-phone-verification', 'VerifyController@resendPhoneVerification');
        Route::post('verify-phone', 'VerifyController@verifyPhone');
        Route::post('login', 'LoginController@login');
        // ForgotPassword
        Route::group(['prefix' => 'password'], function () {
            Route::post('forgot', 'ResetPasswordController@forgotPassword');
            Route::post('resend', 'ResetPasswordController@resend');
            Route::post('code', 'ResetPasswordController@checkCode');
            Route::post('set', 'ResetPasswordController@setNewPassword');
        });
        // AuthedUser
        Route::group([
            'middleware' => JwtTokenIsValid::class,
        ], function () {
            Route::post('logout', 'LoginController@logout');
            Route::group(['prefix' => 'settings'], function () {
                Route::put('/', 'SettingController@updateProfile');
                Route::put('password', 'SettingController@updatePassword');
                Route::post('upload-image', 'SettingController@uploadImageAvatar');
            });
        });
    });

    Route::group([
        'middleware' => JwtTokenIsValid::class,
    ], function () {
        //contact
        Route::group([
            'namespace' => 'Contact'
        ], function () {
            Route::get('contact-types', 'ContactController@contactTypes');
            Route::post('contact', 'ContactController@store');

        });
        //provider
        Route::group([
            'namespace' => 'Provider'
        ], function () {
            Route::get('story-periods', 'StoryController@storyPeriods');
            Route::post('story', 'StoryController@store');
            Route::group(['prefix' => 'product'], function () {
                Route::post('/upload-images', 'ProductController@uploadImages');
                Route::post('/', 'ProductController@store');
            });
        });
    });


});
