<?php

use App\Http\Controllers\MainPageController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'web.'], function () {

    Route::controller(MainPageController::class)->as('main.')->prefix('/')->group(function () {
        Route::get('', 'index')->name('page');
    });

});


