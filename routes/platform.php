<?php

declare(strict_types=1);

use App\Enums\OrchidRoutes;
use App\Orchid\Helpers\OrchidHelper;
use App\Orchid\Screens\Articles\ArticleCategoryEdit;
use App\Orchid\Screens\Articles\ArticleCategoryList;
use App\Orchid\Screens\Articles\ArticleEdit;
use App\Orchid\Screens\Articles\ArticleList;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Seo\RobotsScreen;
use App\Orchid\Screens\Seo\SeoEdit;
use App\Orchid\Screens\Seo\SeoList;
use App\Orchid\Screens\Seo\SitemapScreen;
use App\Orchid\Screens\StaticPages\StaticPageEdit;
use App\Orchid\Screens\StaticPages\StaticPageList;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Main
Route::screen('/home', PlatformScreen::class)->name('platform.main');
Route::screen('/sitemap', SitemapScreen::class)->name(OrchidRoutes::sitemap->base());
Route::screen('/robots', RobotsScreen::class)->name(OrchidRoutes::robot->edit());

OrchidHelper::setAdminRoutes(OrchidRoutes::seo->value, SeoList::class, SeoEdit::class);
OrchidHelper::setAdminRoutes(OrchidRoutes::static->value, StaticPageList::class, StaticPageEdit::class);
OrchidHelper::setAdminRoutes(OrchidRoutes::art_cat->value, ArticleCategoryList::class, ArticleCategoryEdit::class);
OrchidHelper::setAdminRoutes(OrchidRoutes::article->value, ArticleList::class, ArticleEdit::class);

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)->name('platform.profile')->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)->name('platform.systems.users')->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));
