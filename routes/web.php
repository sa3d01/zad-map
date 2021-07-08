<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('admin.home');
});

Route::prefix('/admin')->name('admin.')->namespace('App\Http\Controllers\Admin')->group(function() {
    Route::namespace('Auth')->group(function(){
        Route::get('/login','LoginController@showLoginForm')->name('login');
        Route::post('/login','LoginController@login')->name('login.submit');
        Route::post('/logout','LoginController@logout')->name('logout');
        Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');
    });

    Route::resource('roles', 'RoleController');
    Route::resource('admins', 'AdminsController');


    Route::get('clear-all-notifications', 'NotificationController@clearAdminNotifications')->name('clear-all-notifications');
    Route::get('read-notification/{id}', 'NotificationController@readNotification')->name('read-notification');
    Route::get('settings', 'SettingController@showConfig')->name('settings.edit');
    Route::put('settings', 'SettingController@updateConfing')->name('settings.update');


    Route::get('/profile', 'AdminController@profile')->name('profile');
    Route::put('/profile', 'AdminController@updateProfile')->name('profile.update');

    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('user', 'UserController');
    Route::post('user/{id}/ban', 'UserController@ban')->name('user.ban');
    Route::post('user/{id}/activate', 'UserController@activate')->name('user.activate');


    Route::get('provider/binned', 'ProviderController@binned')->name('provider.binned');
    Route::get('provider/rejected', 'ProviderController@rejected')->name('provider.rejected');
    Route::resource('provider', 'ProviderController');
    Route::get('provider/{id}/reject', 'ProviderController@reject')->name('provider.reject');
    Route::get('provider/{id}/accept', 'ProviderController@accept')->name('provider.accept');


    Route::get('delivery/binned', 'DeliveryController@binned')->name('delivery.binned');
    Route::resource('delivery', 'DeliveryController');
    Route::get('delivery/{id}/reject', 'DeliveryController@reject')->name('delivery.reject');
    Route::get('delivery/{id}/accept', 'DeliveryController@accept')->name('delivery.accept');

    Route::resource('category', 'CategoryController');
    Route::post('category/{id}/ban', 'CategoryController@ban')->name('category.ban');
    Route::post('category/{id}/activate', 'CategoryController@activate')->name('category.activate');

    Route::resource('product', 'ProductController');
    Route::post('product/{id}/ban', 'ProductController@ban')->name('product.ban');
    Route::post('product/{id}/activate', 'ProductController@activate')->name('product.activate');


    Route::get('story/binned', 'StoryController@binned')->name('story.binned');
    Route::get('story/{id}/reject', 'StoryController@reject')->name('story.reject');
    Route::post('story/{id}/accept', 'StoryController@accept')->name('story.accept');

    Route::get('orders/{status}', 'OrderController@list')->name('orders.list');
    Route::resource('order', 'OrderController');
    Route::resource('rate', 'RateController');

    Route::resource('notification', 'NotificationController');
    Route::post('reply-contact/{id}', 'ContactController@replyContact')->name('contact.reply');
    Route::resource('contact', 'ContactController');

    Route::resource('bank', 'BankController');
    Route::post('bank/{id}/ban', 'BankController@ban')->name('bank.ban');
    Route::post('bank/{id}/activate', 'BankController@activate')->name('bank.activate');

    Route::resource('contact_type', 'ContactTypeController');
    Route::post('contact_type/{id}/ban', 'ContactTypeController@ban')->name('contact_type.ban');
    Route::post('contact_type/{id}/activate', 'ContactTypeController@activate')->name('contact_type.activate');

    Route::resource('city', 'CityController');
    Route::post('city/{id}/ban', 'CityController@ban')->name('city.ban');
    Route::post('city/{id}/activate', 'CityController@activate')->name('city.activate');

    Route::resource('district', 'DistrictController');
    Route::post('district/{id}/ban', 'DistrictController@ban')->name('district.ban');
    Route::post('district/{id}/activate', 'DistrictController@activate')->name('district.activate');

    Route::resource('slider', 'SliderController');
    Route::post('slider/{id}/ban', 'SliderController@ban')->name('slider.ban');
    Route::post('slider/{id}/activate', 'SliderController@activate')->name('slider.activate');

    Route::resource('story_period', 'StoryPeriodController');

    Route::resource('wallet-pay', 'WalletPayController');
    Route::post('wallet-pay/{id}/reject', 'WalletPayController@reject')->name('wallet-pay.reject');
    Route::post('wallet-pay/{id}/accept', 'WalletPayController@accept')->name('wallet-pay.accept');

    Route::get('page/{type}/{for}', 'PageController@page')->name('page.edit');
    Route::put('page/{id}', 'PageController@update')->name('page.update');

});
