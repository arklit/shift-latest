<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Services\Crumbchain;
use App\Services\StaticPagesService;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function getStaticPage(string $code)
    {
        $sequence =  array_reverse(explode('/', $code));
        $code = $sequence[0];
        $page = StaticPage::query()->code($code)->active()->firstOrFail();
        $res = StaticPagesService::isSequenceCorrect($page, $sequence);
//
        dd(
            $sequence,
            $code,
            $res,
        );

        abort_if(StaticPagesService::isSequenceCorrect($page, $sequence), 404);
        Crumbchain::makeParentsChain($sp);
        dump(Crumbchain::cs()->getCrumbs());

    }
}
