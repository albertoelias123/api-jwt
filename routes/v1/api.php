<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json(['message' => 'API OK'], 200);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('forgot-password', 'forgotPasswordRequestToken')->name('password.request');
    Route::post('reset-password', 'resetPassword')->name('password.update');
    Route::post('logout', 'logout')->name('logout');
    Route::post('refresh', 'refresh')->name('refreshToken');
    Route::get('authUser', 'authUserDetails')->name('authUser');
});

Route::group(['middleware' => 'auth:api'], function () {

    Route::apiResources([
        'user' => UserController::class
    ]);
});
