<?php

namespace App\Enums;

use Exception;

enum OrchidRoutes: string
{
    case ARTICLES = 'article';
    case ARTICLE_CATEGORIES = 'article-category';
    case SEO = 'seo';
    case ROBOTS = 'robot';
    case SITEMAP = 'sitemap';
    case PAGES = 'pages';
    case SCREEN_CREATOR = 'screen-creator';
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
            self::ARTICLES->value => 'Публикации',
            self::ARTICLE_CATEGORIES->value => 'Категории публикаций',
            self::PAGES->value => 'Страницы',
            self::SEO->value => 'SEO-модуль',
            self::CONFIGURATOR->value => 'Конфигуратор',
            self::ROBOTS->value => 'Robots.txt',
            self::SCREEN_CREATOR->value => 'Создание экрана',
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
            self::ARTICLES->value,
            self::ARTICLE_CATEGORIES->value,
            self::PAGES->value,
            self::SEO->value => false,
            self::CONFIGURATOR->value,
            self::SITEMAP->value,
            self::SCREEN_CREATOR->value,
            self::ROBOTS->value => true,
            default => throw new Exception('Unexpected match value'),
        };
    }
}
