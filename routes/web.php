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
    return redirect('login');
});

Auth::routes();

Route::get('conversations', 'ConversationController@index');
Route::post('conversations', 'ConversationController@store');

Route::get('conversations/{conversation}/users', 'ConversationController@participants');
Route::post('conversations/{conversation}/users', 'ConversationController@join');

Route::get('conversations/{conversation}/messages', 'ConversationController@getMessages');
Route::post('conversations/{conversation}/messages', 'ConversationController@sendMessage');

Route::get('/home', 'HomeController@index')->name('home');
