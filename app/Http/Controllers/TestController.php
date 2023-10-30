<?php

namespace App\Http\Controllers;

use App\Enums\StaticPages;
use App\Services\Crumbchain;
use App\Services\StaticPagesService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected string $managersGuard = 'web';
    protected ?Authenticatable $manager;

    public function __invoke(Request $request)
    {
        $suits = array_column(StaticPages::cases(), 'value');
        dd($suits);
//        $url = request()->path();
//
//        dd($url);
//        $data =
//
//
//            abort_if(!auth($this->managersGuard)->check(), 404);
//        $sp = StaticPage::query()->with('children')->find(5);
//        StaticPagesService::makeCrumbsChainWithNesting($sp);
//
////        Crumbchain::makeParentsChain($sp);
//        dump(
////            $sp,
//            Crumbchain::cs()->getCrumbs()
//        );


    }
}
