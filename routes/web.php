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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('home')
    ->uses('HomeController@index');

Route::get('threads/create')
    ->uses('ThreadController@create')
    ->name('threads.create');

Route::get('threads/{channel?}')
    ->uses('ThreadController@index')
    ->name('threads.index');

Route::get('threads/{channel}/{thread}')
    ->uses('ThreadController@show')
    ->name('threads.show');

Route::delete('threads/{channel}/{thread}')
    ->uses('ThreadController@destroy')
    ->name('threads.destroy');


Route::post('threads')
    ->uses('ThreadController@store')
    ->name('threads.store');

Route::get('threads/{channel}/{thread}/replies')
    ->uses('ReplyController@index')
    ->name('replies.index');

Route::post('threads/{channel}/{thread}/replies')
    ->uses('ReplyController@store')
    ->name('replies.store');

Route::patch('replies/{reply}')
    ->uses('ReplyController@update')
    ->name('replies.update');

Route::delete('replies/{reply}')
    ->uses('ReplyController@destroy')
    ->name('replies.destroy');

Route::post('threads/{channel}/{thread}/subscriptions')
    ->uses('ThreadSubscriptionController@store')
    ->name('subscriptions.store')
    ->middleware('auth');

Route::delete('threads/{channel}/{thread}/subscriptions')
    ->uses('ThreadSubscriptionController@destroy')
    ->name('subscriptions.delete')
    ->middleware('auth');

Route::post('replies/{reply}/favorites')
    ->uses('FavoriteController@store')
    ->name('favorites.store');

Route::delete('replies/{reply}/favorites')
    ->uses('FavoriteController@destroy')
    ->name('favorites.destroy');

Route::get('profiles/{user}')
    ->uses('ProfileController@show')
    ->name('profiles.show');

Route::get('profiles/{user}/notifications')
    ->uses('UserNotificationController@index')
    ->name('notifications.index');

Route::delete('profiles/{user}/notifications/{notification}')
    ->uses('UserNotificationController@destroy')
    ->name('notifications.delete');