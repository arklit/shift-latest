<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\Article;

class FormBuilderController extends Controller
{
    public function getFormConfig(string $code)
    {
        $formConfig = CommonHelper::getPreset('forms.'.$code);
        return response()->json($formConfig);
    }

    public function getOptions()
    {
        $articles = Article::query()->active()->get();
        $options = $articles->map(fn($article) => ['label' => $article->title, 'value' => $article->id]);
        return response()->json($options);
    }
}
