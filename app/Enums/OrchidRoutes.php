<?php

namespace App\Enums;

enum OrchidRoutes: string
{
    case article = 'article';
    case art_cat = 'article_category';
    case vacancy = 'vacancy';
    case seo = 'seo';
    case robot = 'robot';
    case sitemap = 'sitemap';
    case static = 'static_page';
    case conf = 'configurator';


    public function edit()
    {
        return 'platform.' . $this->value . '.edit';
    }

    public function list()
    {
        return 'platform.' . $this->value . '.list';
    }

    public function create()
    {
        return 'platform.' . $this->value . '.create';
    }
}
