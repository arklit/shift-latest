<?php

use App\Enums\OrchidRoutes;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

$main = 'web.main.page';

// АДМИНКА =============
$admin = 'platform.main';
// /admin/main
Breadcrumbs::for($admin, fn(Trail $t) => $t->push('Главная', route($admin)));

// /admin/products
Breadcrumbs::for(OrchidRoutes::article->list(), fn(Trail $t) => $t->parent($admin)
    ->push('Список статей', route(OrchidRoutes::article->list()))
);

Breadcrumbs::for(OrchidRoutes::art_cat->list(), fn(Trail $t) => $t->parent($admin)
    ->push('Список категорий статей', route(OrchidRoutes::art_cat->list()))
);

Breadcrumbs::for(OrchidRoutes::conf->list(), fn(Trail $t) => $t->parent($admin)
    ->push('Конфигуратор', route(OrchidRoutes::conf->list()))
);

