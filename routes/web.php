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
    return view('welcome');
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


    Route::get('story/binned', 'StoryController@binned')->name('story.binned');
    Route::get('story/{id}/reject', 'StoryController@reject')->name('story.reject');
    Route::get('story/{id}/accept', 'StoryController@accept')->name('story.accept');

    Route::get('order/new', 'OrderController@new')->name('order.new');
    Route::get('order/pre_paid', 'OrderController@pre_paid')->name('order.pre_paid');
    Route::get('order/in_progress', 'OrderController@in_progress')->name('order.in_progress');
    Route::get('order/completed', 'OrderController@completed')->name('order.completed');
    Route::get('order/rejected', 'OrderController@rejected')->name('order.rejected');

    Route::resource('notifications', 'NotificationController');


});
