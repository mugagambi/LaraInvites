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

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/users','UsersController@index')->name('users');
Route::get('/users/invite','UsersController@invite_view')->name('invite_view');
Route::post('/users/invite','UsersController@invite')->name('invite_post');
Route::get('/users/accept/{token}','UsersController@accept_invite')->name('accept');
Route::post('/users/accept/','Auth\RegisterController@register')->name('accept_invite');


