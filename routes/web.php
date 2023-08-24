<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/super-test', TestController::class)->name('test-get');
Route::get('/sitemap.xml', SitemapController::class)->name('xml-map');
Route::get('/robots.txt', RobotsTxtController::class)->name('robots-txt');


Route::group(['prefix' => 'ajax'], function () {
    // Методы для AJAX-запросов


});

Route::group(['as' => 'web.'], function () {
    Route::controller(MainPageController::class)->as('main.')->prefix('/')->group(function () {
        Route::get('/', 'index')->name('page');
    });

    Route::controller(BlogController::class)->as('articles.')->prefix('/articles')->group(function () {
        Route::get('/', 'getArticlesList')->name('list');
        Route::get('/{categoryCode}', 'getArticlesCategory')->name('category');
        Route::get('/{categoryCode}/{articleSlug}', 'getArticlePage')->name('page');
    });

    Route::controller(PagesController::class)->as('pages.')->prefix('/')->group(function () {
        Route::get('/{staticPageCode}', 'getStaticPage')->name('static')
            ->where('staticPageCode', '.*');;
    });



});


