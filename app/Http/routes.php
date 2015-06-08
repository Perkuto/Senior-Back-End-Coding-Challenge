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


Route::get('user/{user_id}/images', [
    'as' => 'user_pictures', 'uses' => 'UserController@listImages'
]);

Route::delete('user/image/{id}', [
    'as' => 'user_image_delete', 'uses' => 'UserController@deleteImage'
]);

Route::put('user/image/{id}', [
    'as' => 'user_image_update', 'uses' => 'UserController@updateImage'
]);

Route::post('user/image', [
    'as' => 'user_image_add', 'uses' => 'UserController@addImage'
]);

Route::any('mock/authenticate/{email}/{password}', [
    'as' => 'mock_authenticate', 'uses' => 'UserController@mockAuthenticate'
]);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
