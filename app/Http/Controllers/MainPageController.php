<?php

namespace App\Http\Controllers;

use App\Enums\ClientRoutes;
use App\Helpers\CommonHelper;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\ArticlesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainPageController extends Controller
{
    public function index()
    {
        return view('layout');
    }
}
