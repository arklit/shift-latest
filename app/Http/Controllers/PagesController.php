<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Page;
use App\Repositories\CatalogRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public string $menuTitle;
    public array $breadcrumbs;

    public function prepareMenu($code)
    {
        $page = Page::query()->active()->where('code', $code)->first();
        $this->menuTitle = $page->title;

        return Page::query()->active()->where('parent_id', $page->id)->get()->each(
            function ($item) use ($page) {
                $item->active_menu = $item->uri === $page->uri;
            });
    }

    public function getPage($params = null)
    {
        $page = Page::query()->active()->with(['children'])->where('uri', '/' . $params)->firstOrFail();

        $page->setBreadCrumbs();

        $parent = null;
        if ($page->parent_id) {
            $parent = Page::query()->where('parent_id', $page->parent->id)->with('children')->firstOrFail();
        }

        return view("pages.default", compact('page', 'parent'));
    }
}
