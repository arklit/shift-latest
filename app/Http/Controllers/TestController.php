<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use App\Services\Crumbchain;
use Illuminate\Contracts\Auth\Authenticatable;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Tabuna\Breadcrumbs\Trail;

class TestController extends Controller
{
    protected string $managersGuard = 'web';
    protected ?Authenticatable $manager;

    public function __invoke()
    {
        abort_if(!auth($this->managersGuard)->check(), 404);
        $sp = StaticPage::query()->with('children')->find(4);
        Crumbchain::makeParentsChain($sp);
        dump($sp, Crumbchain::get()->getCrumbs());



    }
}
