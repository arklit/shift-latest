<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class CatalogController extends Controller
{
    public function getCatalogPage(Request $request)
    {
        return response()->setStatusCode(404);
    }
    public function getGroupPage(string $groupCode, Request $request)
    {
        return response()->setStatusCode(404);
    }
    public function getCategoryPage(string $groupCode, string $categoryCode, Request $request)
    {
        return response()->setStatusCode(404);
    }
    public function getGoodPage(int $id, Request $request)
    {
        return view('catalog.good-page', ['good' => null]);
    }
}
