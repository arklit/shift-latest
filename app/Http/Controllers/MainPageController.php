<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Development;
use App\Models\Product;
use App\Models\Technology;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    /**
     * Роут главной страниы
     * @method GET
     * @return
     */
    public function index(Request $request)
    {
        $developments = Development::query()->active()->isMain()->sorted('asc')->limit(8)->get();
        $technologies = Technology::query()->active()->isMain()->sorted('asc')->limit(8)->get();
        $products = Product::query()->active()->isMain()->sorted('asc')->limit(8)->get();
        $articles = Article::query()->active()->publicated()->publicationSorted()->limit(10)->get();
        // TODO указать имя шаблона
//        return $this->responseData(compact('developments', 'technologies', 'products', 'articles'));
        return view('pages.main-page', compact('developments', 'technologies', 'products', 'articles'));
    }
}
