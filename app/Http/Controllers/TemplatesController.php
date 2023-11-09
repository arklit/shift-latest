<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\PagesService;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    public function getCompanyPage(Request $request)
    {
        $page = Page::query()->where('uri', '/'.$request->path())->first();
        $page->setBreadCrumbs();

        return view('pages.templates.about', compact('page'));
    }
}
