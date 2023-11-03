<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use function App\Orchid\Screens\Pages\buildTree;

class PagesController extends Controller
{
    public function getPage($params = null)
    {
        $page = Page::query()->active()->with(['children'])->where('uri', '/' . $params)->first();
        abort_if(!$page, 404);
        $page->setBreadCrumbs();

        if ($page->parent_id) {
            abort_if(!$page->parent, 404);
            $page = Page::query()->where('parent_id', $page->parent->id)->with('children')->first();
        }

        return view("pages.default", compact('page'));
    }

    public function search(Request $request)
    {
        $name = $request->input('name');
        $item = Page::where('name', $name)->first();
        $tree = $item->ancestorsAndSelf($item->id)->reverse();
        $pages = collect([$this->buildSearchTree($tree)])->toArray();

        return view('admin.page-tree.container', compact('pages'));
    }

    public function getTree()
    {
        $pages = Page::query()->withDepth()->with('ancestors')->get()->toTree()->toArray();

        return view('admin.page-tree.container', compact('pages'));
    }

    public function buildSearchTree($array)
    {
        $parent = null;
        foreach ($array as $item) {
            if ($parent) {
                $item['children'] = [$parent];
                $parent = $item->toArray();
            } else {
                $parent = $item->toArray();
            }
        }

        return $parent;
    }
}
