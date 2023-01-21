<?php

use App\Presentation\Http\Controllers\Api\V1\Auth\AuthController;
use App\Presentation\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Presentation\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Presentation\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register/member', [AuthController::class, "actionRegister"])->name('api.register');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, "actionLogin"])->name('api.auth.login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, "actionLogout"])->name('api.auth.logout');
        Route::post('/refresh-token', [AuthController::class, "actionRefreshToken"])->name('api.auth.refreshToken');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/users/{orderBy?}/{sort?}', [UserController::class, "actionAll"])->name('api.user.all');

    Route::post('/users/search', [UserController::class, "actionSearch"])->name('api.user.search');
    Route::group(['prefix' => 'users'], function () {
        Route::post('/search/page', [UserController::class, "actionSearchPage"])->name('api.user.search.page');
    });

    Route::get('/user/{id}', [UserController::class, "actionShow"])->name('api.user.get');

    Route::post('/user/', [UserController::class, "actionStore"])->name('api.user.store');
    Route::put('/user/{id}', [UserController::class, "actionUpdate"])->name('api.user.update');
    Route::delete('/user/{id}', [UserController::class, "actionDestroy"])->name('api.user.destroy');

    Route::group(['prefix' => 'user'], function () {
        Route::get('/follow/{userId}', [UserController::class, "actionFollow"])->name('api.user.follow');
    });
});


