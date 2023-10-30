<?php

namespace App\Http\Controllers;

use App\Services\Crumbchain;
use App\Services\StaticPagesService;

class PagesController extends Controller
{
    public function getStaticPage(string $code)
    {
//        $sequence = array_reverse(explode('/', $code));
//        $code = $sequence[0];
//        $page = StaticPage::query()->code($code)->with('children')->active()->firstOrFail();
//
//        abort_if(!StaticPagesService::isSequenceCorrect($page, $sequence), 404);
//
//        Crumbchain::makeCrumb('Главная', route('web.main.page'));
//        StaticPagesService::makeCrumbsChainWithNesting($page);
//
//        if (!$page->children->isEmpty()) {
//            foreach ($page->children as $child) {
//                $child->fullPath = StaticPagesService::makeLinkForChildren($child);
//            }
//        }
//
//        $crumbs = Crumbchain::cs()->getCrumbs();
//        $crumbs->first->deactivate();
////        dd(
////        );
//
//        dump(Crumbchain::cs()->getCrumbs(), $page->children);
    }
}
