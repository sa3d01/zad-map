<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\JwtTokenIsValid;

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
    'prefix' => 'v1',
], function () {
    // AUTH
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {
        Route::post('register', 'RegisterController@register');
        Route::post('resend-phone-verification', 'VerifyController@resendPhoneVerification');
        Route::post('verify-phone', 'VerifyController@verifyPhone');
        Route::post('login', 'LoginController@login');
        // ForgotPassword
        Route::group(['prefix' => 'password'], function () {
            Route::put('update', 'SettingController@updatePassword');
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
            Route::post('upload-image', 'SettingController@uploadImage');
            Route::put('update', 'SettingController@updateProfile');
        });
    });
    // General
    Route::group(['namespace' => 'General', 'prefix' => 'general'], function () {
        Route::get('settings', 'SettingController@getSettings');
        Route::get('cities', 'DropDownController@cities');
        Route::get('cities/{cityId}/districts', 'DropDownController@districts');
        Route::get('pages/{user_type}/{type}', 'PageController@getPage');
    });
    //Home
    Route::group(['namespace' => 'Home', 'prefix' => 'home'], function () {
        Route::get('slider', 'SliderController@index');
        Route::get('story', 'StoryController@index');
        Route::get('providers-map', 'ProviderController@providersMap');
        Route::get('product/{id}', 'ProductController@show');
    });
    //Category
    Route::group(['prefix' => 'category','namespace' => 'Category'], function () {
        Route::get('/', 'CategoryController@index');
        Route::get('/{category_id}/provider', 'CategoryController@providers');
        Route::get('/{category_id}/provider/{provider_id}/products', 'CategoryController@products');
    });
    //Profile
    Route::get('provider/{provider_id}', 'Provider\ProviderController@show');
    Route::get('provider/{provider_id}/products', 'Provider\ProductController@list');
    //Authed end points
    Route::group([
        'middleware' => JwtTokenIsValid::class,
    ], function () {
        //Contact
        Route::group([
            'namespace' => 'Contact'
        ], function () {
            Route::get('contact-types', 'ContactController@contactTypes');
            Route::post('contact', 'ContactController@store');

        });
        //Provider
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
        //Cart
        Route::group([
            'namespace' => 'Cart',
        ], function () {
            Route::post('cart', 'CartController@addListToCart');
            Route::post('product/{product_id}/cart', 'CartController@editCart');
            Route::group(['prefix' => 'cart'], function () {
                Route::get('/', 'CartController@index');
                Route::put('/update-counts', 'CartController@updateCounts');
            });
        });
        //Notification
        Route::group([
            'namespace' => 'Notification',
        ], function () {
            Route::group(['prefix' => 'notifications'], function () {
                Route::get('/', 'NotificationController@index');
                Route::get('/{id}', 'NotificationController@show');
            });
        });
        //Order
        Route::group([
            'namespace' => 'Order',
        ], function () {
            Route::group(['prefix' => 'order'], function () {
                Route::post('/', 'OrderController@store');
                Route::get('/{status}/filter', 'OrderController@filteredOrders');
                Route::get('/{id}', 'OrderController@show');
                Route::put('/{id}', 'OrderController@update');
                Route::post('/{id}/cancel', 'OrderStatusController@cancelOrder');
                Route::post('/{id}/accept', 'OrderStatusController@acceptOrder');
                Route::post('/{id}/payment', 'OrderStatusController@payOrder');
            });
        });
        //Chat
        Route::group([
            'namespace' => 'Chat',
        ], function () {
            Route::group(['prefix' => 'chat'], function () {
                Route::get('/', 'ChatController@getConversations');
                Route::get('/{receiver_id}', 'ChatController@getMessages');
                Route::post('/', 'ChatController@store');
            });
        });
    });


});
