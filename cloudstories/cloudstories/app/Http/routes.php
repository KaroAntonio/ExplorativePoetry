<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

get('stories', 'StoriesController@index');

Route::get('/', 'StoriesController@begin');

Route::get('home', 'HomeController@index');

Route::get('/story/{id}', ['uses' =>'StoriesController@show']);

Route::post('story', 'StoriesController@store');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
