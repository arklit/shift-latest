<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Services\Crumbchain;
use App\Services\StaticPagesService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

class TestController extends Controller
{
    protected string $managersGuard = 'web';
    protected ?Authenticatable $manager;

    public function __invoke(Request $request)
    {
        $url = request()->path();

        dd($url);
        $data =


        abort_if(!auth($this->managersGuard)->check(), 404);
        $sp = StaticPage::query()->with('children')->find(5);
        StaticPagesService::makeParentsChainWithNesting($sp);

//        Crumbchain::makeParentsChain($sp);
        dump(
//            $sp,
            Crumbchain::cs()->getCrumbs()
        );



    }
}
