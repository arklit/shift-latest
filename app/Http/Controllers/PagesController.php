<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Services\Crumbchain;

class PagesController extends Controller
{
    public function getStaticPage(string $code)
    {
        $sp = StaticPage::query()->code($code)->firstOrFail();
        Crumbchain::makeParentsChain($sp);
        dump(Crumbchain::get()->getCrumbs());

    }
}
