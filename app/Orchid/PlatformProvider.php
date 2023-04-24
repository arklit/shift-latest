<?php

declare(strict_types=1);

namespace App\Orchid;

use App\Enums\OrchidRoutes;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [

            Menu::make('Публикации')->icon('feed')->list([
                Menu::make('Статьи')->route(OrchidRoutes::article->list())->icon('book-open'),
                Menu::make('Категории статей')->route(OrchidRoutes::art_cat->list())->icon('list'),
            ]),

            Menu::make('SEO')->icon('globe')->list([
                Menu::make('SEO страницы')->route(OrchidRoutes::seo->list())->icon('docs'),
                Menu::make('Robots.txt')->route(OrchidRoutes::robot->edit())->icon('android'),
                Menu::make('Карта')->route(OrchidRoutes::sitemap->base())->icon('map'),
            ]),
//            Menu::make('Конфигуратор')->route(OrchidRoutes::conf->edit(), ['id' => 1])->icon('settings'),

            Menu::make(__('Users'))->icon('user')->route('platform.systems.users')
                ->permission('platform.systems.users')->title(__('Access rights')),

            Menu::make(__('Roles'))->icon('lock')->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make(__('Profile'))->route('platform.profile')->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
