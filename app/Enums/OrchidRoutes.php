<?php

namespace App\Enums;

enum OrchidRoutes: string
{
    case article = 'article';
    case art_cat = 'article-category';
    case vacancy = 'vacancy';
    case seo = 'seo';
    case robot = 'robot';
    case sitemap = 'sitemap';
    case static = 'static-page';
    case conf = 'configurator';


    public function list(): string
    {
        return 'platform.' . $this->value . '.list';
    }

    public function create(): string
    {
        return 'platform.' . $this->value . '.create';
    }

    public function edit(): string
    {
        return 'platform.' . $this->value . '.edit';
    }

    public function base()
    {
        return 'platform.' . $this->value;
    }

    public function getTitle()
    {
        return match ($this->value) {
            self::article->value => 'Список статей',
            self::art_cat->value => 'Список категорий статей',
            self::static->value => 'Список статических страниц',
            self::seo->value => 'SEO-страницы',
            self::conf->value => 'Конфигуратор',
            self::robot->value => 'Robots.txt',
            self::sitemap->value => 'Карта сайта',
            default => throw new \Exception('Unexpected match value'),
        };
    }

    public function isSingle(): bool
    {
        return match ($this->value) {
            self::article->value,
            self::art_cat->value,
            self::static->value,
            self::seo->value => false,
            self::conf->value,
            self::sitemap->value,
            self::robot->value => true,
            default => throw new \Exception('Unexpected match value'),
        };
    }
}
