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
use Illuminate\Support\Str;

class AjaxController extends Controller
{
    public function validateForm(FormRequest $request)
    {
        $formData = $request->input('item');

        if ($formData['modal_id'] === 'createOrUpdateSeoPage')
        {
            $formData['url'] = Str::finish(Str::start($formData['url'], '/'), '/');
            $rules = [
                'title' => ['bail', 'required', 'max:160'],
                'url' => ['bail', 'required', 'unique:seos'],
                'description' => ['bail'],
            ];

            $messages = [
                'title.required' => 'Введите заголовок',
                'title.max' => 'Заголовок не может быть длиннее 160 символов',
                'url.required' => 'Введите URL',
                'url.max' => 'URL не может быть длиннее 60 символов',
                'url.unique' => 'Страница с таким URL уже добавлена',
            ];

            $validator = Validator::make($formData, $rules, $messages);

            if ($validator->fails()) {
                $errors = collect($validator->messages()->messages())->map(fn ($error, $name) => $error[0])->toArray();
                return response()->json(['success' => false, 'errors' => $errors]);
            }
        } else {
            return response()->json(['success' => true, 'message' => 'notFoundForm']);
        }

        return response()->json(['success' => true]);
    }
}
