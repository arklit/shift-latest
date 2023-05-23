<?php

namespace App\Services;

use App\DTO\CrumbDTO;
use App\Models\StaticPage;

class StaticPagesService
{
    public const ROUTE = 'web.pages.static';

    public static function isSequenceCorrect(StaticPage $page, array $sequence)
    {
        $currentNode = $page;
        foreach ($sequence as $point) {
            if ($currentNode->code !== $point) {
                return false;
            }
            if (!is_null($currentNode->getRelation('parent'))) {
                $currentNode = $currentNode->parent;
            }
        }
        return true;
    }

    public static function tt(StaticPage $node, string $point)
    {
        if (!is_null($node->getRelation('parent'))) {
            self::tt($node->parent, $point);
        }
    }


    public static function makeParentsChainWithNesting(StaticPage $page)
    {
        if (!is_null($page->getRelation('parent'))) {
            $code = self::makeParentsChainWithNesting($page->parent) . '/' . $page->code;
            Crumbchain::cs()->makeCrumb($page->title, route(self::ROUTE, $code));
            return $code;
        }
        Crumbchain::makeCrumb('Главная', route('web.main.page'));
        return '/';
    }

    public static function makeParentsChain(StaticPage $page)
    {
        if (!is_null($page->getRelation('parent'))) {
            $dto = CrumbDTO::make($page->title, route(self::ROUTE, $page->code));
            self::makeParentsChain($page->parent);
            Crumbchain::cs()->addCrumb($dto);
        } else {
            Crumbchain::makeCrumb('Главная', route('web.main.page'));
        }
    }
}
