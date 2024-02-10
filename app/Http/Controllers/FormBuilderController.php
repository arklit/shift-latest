<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\FormsConfig;
use App\Models\Article;

class FormBuilderController extends Controller
{
    public function getFormConfig(string $code): \Illuminate\Http\JsonResponse
    {
        $formConfig = FormsConfig::getFormByKey($code);
        return response()->json($formConfig);
    }

    public function getOptions()
    {
        $articles = Article::query()->active()->get();
        $options = $articles->map(fn($article) => ['label' => $article->title, 'value' => $article->id]);
        return response()->json($options);
    }
}
