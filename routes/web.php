<?php

use App\Http\Controllers\MainPageController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/test', TestController::class)->name('test-get');
Route::get('/sitemap.xml', SitemapController::class)->name('xml-map');
Route::get('/robots.txt', RobotsTxtController::class)->name('robots-txt');

Route::group(['as' => 'web.'], function () {

    Route::controller(MainPageController::class)->as('main.')->prefix('/')->group(function () {
        Route::get('', 'index')->name('page');
    });



});


