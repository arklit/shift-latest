<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ScreenGeneratorService
{
    public function generateListScreen(string $screenName, string $modelName, array $fields): void
    {
        // Путь к шаблону
        $listScreenStubPath = app_path('Orchid/Console/Stubs/list-screen.php');

        // Читаем содержимое шаблона
        $listScreenContent = File::get($listScreenStubPath);

        // Генерируем строки для полей
        $fieldsString = '';
        foreach ($fields as $field) {
            if ($field['is_list']) {
                $fieldsString .= "TD::make('{$field['code']}', '{$field['name']}')->sort()->filter(),\n";
            }
        }

        // Заменяем плейсхолдеры на данные пользователя и сгенерированные поля
        $listScreenContent = str_replace(['DirectoryName', 'ProtoModel', 'StubListScreen', '//..fields', 'PROTO_MODEL'], [$screenName, $modelName, $screenName . 'ListScreen', $fieldsString, strtoupper(Str::snake($modelName))], $listScreenContent);

        // Путь для нового файла
        $newListScreenPath = app_path('Orchid/Screens/' . $screenName . '/' . $screenName . 'ListScreen.php');

        // Создаем директорию, если она не существует
        if (!File::exists(dirname($newListScreenPath))) {
            File::makeDirectory(dirname($newListScreenPath), 0777, true);
        }

        // Сохраняем новый файл
        File::put($newListScreenPath, $listScreenContent);
    }

    public function generateEditScreen(string $screenName, string $modelName, array $fields): void
    {
        // Путь к шаблону
        $editScreenStubPath = app_path('Orchid/Console/Stubs/edit-screen.php');

        // Читаем содержимое шаблона
        $editScreenContent = File::get($editScreenStubPath);

        // Генерируем строки для полей
        $fieldsString = '';
        foreach ($fields as $field) {
            if ($field['is_edit']) {
                $fieldsString .= "{$field['type']}::make('item.{$field['code']}')->title('{$field['name']}')->required(),\n";
            }
        }

        // Заменяем плейсхолдеры на данные пользователя и сгенерированные поля
        $editScreenContent = str_replace(['DirectoryName', 'ProtoModel', 'StubEditScreen', '//..fields', 'PROTO_MODEL'], [$screenName, $modelName, $screenName . 'EditScreen', $fieldsString, strtoupper(Str::snake($modelName))], $editScreenContent);

        // Путь для нового файла
        $newEditScreenPath = app_path('Orchid/Screens/' . $screenName . '/' . $screenName . 'EditScreen.php');

        // Создаем директорию, если она не существует
        if (!File::exists(dirname($newEditScreenPath))) {
            File::makeDirectory(dirname($newEditScreenPath), 0777, true);
        }

        // Сохраняем новый файл
        File::put($newEditScreenPath, $editScreenContent);
    }

    public function generateModel(string $modelName, string $tableName, array $fields): void
    {
        // Путь к шаблону
        $modelStubPath = app_path('Orchid/Console/Stubs/model.php');

        // Читаем содержимое шаблона
        $modelContent = File::get($modelStubPath);

        // Генерируем строки для полей
        $allowedSorts = '';
        $allowedFilters = '';
        foreach ($fields as $field) {
            $allowedSorts .= "'{$field['code']}', ";
            $allowedFilters .= "'{$field['code']}' => Like::class, ";
        }

        // Заменяем плейсхолдеры на данные пользователя и сгенерированные поля
        $modelContent = str_replace(['ModelName', 'model_table', '//..allowedSorts', '//..allowedFilters'], [$modelName, $tableName, $allowedSorts, $allowedFilters], $modelContent);

        // Путь для нового файла
        $newModelPath = app_path('Models/' . $modelName . '.php');

        // Создаем директорию, если она не существует
        if (!File::exists(dirname($newModelPath))) {
            File::makeDirectory(dirname($newModelPath), 0777, true);
        }

        // Сохраняем новый файл
        File::put($newModelPath, $modelContent);
    }
}
