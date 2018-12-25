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
    return view('app');
});


Route::group(['prefix' => 'audios'], function () {
    Route::get('/', 'AudioController@store');
    Route::get('/edit/{id}', 'AudioController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'AudioController@search');
    Route::post('/create', 'AudioController@create');
    Route::post('/update/{id}', 'AudioController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'AudioController@destroy')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'genres'], function () {
    Route::get('/', 'GenreController@store');
    Route::get('/{id}', 'GenreController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'GenreController@search');
    Route::post('/create', 'GenreController@create');
    Route::post('/update/{id}', 'GenreController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'GenreController@destroy')
        ->where('id', '[0-9]+');;
});

Route::group(['prefix' => 'videos'], function () {
    Route::get('/', 'VideoController@store');
    Route::get('/{id}', 'VideoController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'VideoController@search');
    Route::post('/create', 'VideoController@create');
    Route::post('/update/{id}', 'VideoController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'VideoController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'VideoController@form_info');
});

Route::group(['prefix' => 'screens'], function () {
    Route::get('/', 'ScreenController@store');
    Route::get('/{id}', 'ScreenController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'ScreenController@search');
    Route::post('/create', 'ScreenController@create');
    Route::post('/update/{id}', 'ScreenController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'ScreenController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'ScreenController@form_info');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@store');
    Route::get('/{id}', 'UserController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'UserController@search');
    Route::post('/create', 'UserController@create');
    Route::post('/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'UserController@destroy')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@store');
    Route::get('/edit/{id}', 'ProductController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'ProductController@search');
    Route::post('/create', 'ProductController@create');
    Route::post('/update/{id}', 'ProductController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'ProductController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'ProductController@form_info');
});

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'OrderController@store');
    Route::get('/{id}', 'OrderController@form')
        ->where('id', '[0-9]+');
    Route::get('/search', 'OrderController@search');
    Route::post('/create', 'OrderController@create');
    Route::post('/update/{id}', 'OrderController@update')
        ->where('id', '[0-9]+');
    Route::post('/delete/{id}', 'OrderController@destroy')
        ->where('id', '[0-9]+');
    Route::get('/form_info', 'OrderController@form_info');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');
Route::get('/auth', 'UserController@getUser');
