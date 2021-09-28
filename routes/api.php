<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\User;

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



// user login and register api
Route::post('/register', 'AuthApiController@register');
Route::post('/login', 'AuthApiController@login');

// user crud operations 
Route::apiResource('/user', 'API\UserController');

//  authenticate user access
Route::post('/user/updateHobbies', 'API\HobbyController@updatehobbies')->middleware('auth:api');


// only super admin access
Route::group(['middleware' => 'super.admin'], function () {
    Route::post('/userlist','API\UserController@getUserByHobbies');
});