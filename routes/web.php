<?php

use App\Helpers\Constants;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RobotsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\Pages;
use Illuminate\Support\Facades\Route;

Route::get('/super-test', TestController::class)->name('test-get');
Route::get('/sitemap.xml', SitemapController::class)->name('xml-map');
Route::get('/robots.txt', RobotsTxtController::class)->name('robots-txt');

Route::group(['prefix' => 'ajax'], function () {
    Route::post('/search-tree', [PagesController::class, 'search'])->name('search');
    Route::get('/get-tree', [PagesController::class, 'getTree'])->name('get.tree');
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

    Route::controller(TemplatesController::class)->middleware(Pages::class)->as('templates.')->prefix('/')->group(function () {
        Route::get('/about/company', 'getCompanyPage')->name('about');
    });

    Route::controller(PagesController::class)->middleware(Pages::class)->as('pages.')->prefix('/')->group(function () {
        Route::get('/{params?}', 'getPage')->name('list')->where('params', '.*');
    });
});


