<?php

namespace App\Enums;

use Exception;

enum OrchidRoutes: string
{
    case ARTICLE_CATEGORY = 'article-category';
    case ARTICLE = 'article';
    //case-place
    case SEO = 'seo';
    case ROBOTS = 'robot';
    case SITEMAP = 'sitemap';
    case PAGES = 'pages';
    case CONFIGURATOR = 'configurator';

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

    /**
     * @throws Exception
     */
    public function getTitle(): string
    {
        return match ($this->value) {
            self::ARTICLE_CATEGORY->value => 'Категории статей',
            self::ARTICLE->value => 'Статьи',
            //title-place
            self::PAGES->value => 'Страницы',
            self::SEO->value => 'SEO-модуль',
            self::CONFIGURATOR->value => 'Конфигуратор',
            self::ROBOTS->value => 'Robots.txt',
            self::SITEMAP->value => 'Карта сайта',
            default => throw new Exception('Unexpected match value'),
        };
    }

    /**
     * @throws Exception
     */
    public function isSingle(): bool
    {
        return match ($this->value) {
            self::ARTICLE_CATEGORY->value => false,
            self::ARTICLE->value => false,
            //single-place
            self::PAGES->value,
            self::SEO->value => false,
            self::CONFIGURATOR->value,
            self::SITEMAP->value,
            self::ROBOTS->value => true,
            default => throw new Exception('Unexpected match value'),
        };
    }
}
