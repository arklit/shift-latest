<?php

namespace App\Services;

use App\Models\StaticPage;

class StaticPagesService
{
    public const ROUTE = 'web.pages.static';

    public static function isSequenceCorrect(StaticPage $page, array $sequence)
    {
        $codes = [];
        $currentNode = $page;

        while(true) {
            $codes[] = $currentNode->code;
            if (!is_null($currentNode->getRelation('parent'))) {
                $currentNode = $currentNode->parent;
            } else {
                break;
            }
        }

        if (count($codes) !== count($sequence)) {
            return false;
        }

        foreach ($codes as $index => $code) {
            if (empty($sequence[$index]) || $code !== $sequence[$index]) {
                return false;
            }
        }
        return true;
    }

    public static function makeCrumbsChainWithNesting(StaticPage $page)
    {
        $code = is_null($page->parent_id) ? $page->code : self::makeCrumbsChainWithNesting($page->parent) . '/' . $page->code;
        Crumbchain::cs()->makeCrumb($page->title, route(self::ROUTE, $code));
        return $code;
    }

    public static function makeLinkForChildren(StaticPage $page)
    {
        $codes = [];
        $currentNode = $page;
        while(true) {
            $codes[] = $currentNode->code;
            if (!is_null($currentNode->getRelation('parent'))) {
                $currentNode = $currentNode->parent;
            } else {
                break;
            }
        }

        $codes = array_reverse($codes);
        return implode('/', $codes);
    }
}
