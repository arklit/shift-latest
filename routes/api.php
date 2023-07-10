<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
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

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:customers');
    Route::post('register', 'register');
    Route::post('password/recover-request', 'passwordRecoverRequest');
    Route::post('password/recover', 'passwordRecover');
    Route::get('user', 'getCurrentUser')->middleware('auth:customers');
});


Route::controller(CabinetController::class)
    ->prefix('cabinet')->middleware('auth:customers')->group(function () {

        Route::prefix('goods')->group(function () {
            Route::get('groups', 'getGoodsGroups');
            Route::get('filter', 'getFilter');
            Route::get('list', 'getGoodsByFilter');
        });

        Route::prefix('basket')->group(function () {
            Route::get('list', 'getBasketsList');
            Route::post('create', 'createBasket');
            Route::get('{basketId}/get', 'getBasket');
            Route::delete('{basketId}/delete', 'deleteBasket');
            Route::delete('{basketId}/items/{goodId}/change', 'changeBasketGoodCount');
            Route::delete('{basketId}/items/{goodId}/delete', 'getBasket');
        });

    });
