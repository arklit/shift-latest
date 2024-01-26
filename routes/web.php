<?php

use App\Helpers\Constants;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\OrchidController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SimpleFormsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\Pages;
use Illuminate\Support\Facades\Route;

Route::get('/super-test', TestController::class)->name('test-get');
Route::get('/sitemap.xml', SitemapController::class)->name('xml-map');
Route::get('/robots.txt', RobotsTxtController::class)->name('robots-txt');

Route::controller(OrchidController::class)->as('orchid.')->prefix('/orchid')->group(function () {
    Route::post('/send-modal', 'validateForm')->name('validate.modals');
});

Route::group(['as' => 'forms.'], function () {
    Route::controller(SimpleFormsController::class)->as('forms.')->prefix('/forms')->group(function () {
        Route::get('/{code}/get', 'getForm')->name('get');
        Route::post('/{code}/send', 'sendForm')->name('send');
    });
});

Route::group(['as' => 'web.'], function () {
    Route::controller(MainPageController::class)->as('main.')->prefix('/')->group(function () {
        Route::get('/', 'index')->name('page');
    });

    Route::controller(BlogController::class)->as('articles.')->prefix('/articles')->group(function () {
        Route::get('/', 'getArticlesList')->name('list');
        Route::get('/p{page}', 'getArticlesList')->name('list.page')->where(['page' => Constants::REGEX_ID]);
        Route::get('/{categoryCode}', 'getArticlesCategory')->name('category');
        Route::get('/{categoryCode}/p{page}', 'getArticlesCategory')->name('category.page')->where(['page' => Constants::REGEX_ID]);
        Route::get('/{categoryCode}/{articleSlug}', 'getArticlePage')->name('card');
    });

    Route::controller(PagesController::class)->middleware(Pages::class)->as('pages.')->prefix('/')->group(function () {
        Route::get('/{params?}', 'getPage')->name('page')->where('params', '.*');
    });
});


