<?php

namespace App\Http\Controllers;

use App\Enums\ClientRoutes;
use App\Helpers\CommonHelper;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\ArticlesService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller
{
    public function validateForm(FormRequest $request)
    {
        // Получение данных формы
        $formData = $request->input('item');

        $rules = [
            'url' => 'required',
            'title' => 'required',
        ];

        $messages = [
            'url.required' => 'Поле 1 обязательно для заполнения',
            'title.required' => 'Поле 2 обязательно для заполнения',
        ];

        $validator = Validator::make($formData, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['success' => false, 'errors' => $errors]);
        }

        return response()->json(['success' => true]);
    }
}
