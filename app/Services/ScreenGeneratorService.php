<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ScreenGeneratorService
{
    public function generateListScreen(string $modelName, array $fields, bool $useModal = false): void
    {
        // Путь к шаблону
        $stubFileName = $useModal ? 'list-screen-modal.php' : 'list-screen.stub';
        $listScreenStubPath = app_path('Orchid/Console/Stubs/' . $stubFileName);

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
        $listScreenContent = str_replace(['DirectoryName', 'ProtoModel', 'StubListScreen', '//..fields', 'PROTO_MODEL'], [$modelName, $modelName, $modelName . 'ListScreen', $fieldsString, strtoupper(Str::snake($modelName))], $listScreenContent);

        // Путь для нового файла
        $newListScreenPath = app_path('Orchid/Screens/' . $modelName . '/' . $modelName . 'ListScreen.php');

        // Создаем директорию, если она не существует
        if (!File::exists(dirname($newListScreenPath))) {
            File::makeDirectory(dirname($newListScreenPath), 0777, true);
        }

        // Сохраняем новый файл
        File::put($newListScreenPath, $listScreenContent);

        // Изменяем права доступа к файлу
        File::chmod($newListScreenPath, 0777);

        if ($useModal) {
            $this->generateModal($modelName, $fields);
        }
    }

    public function generateModal(string $modelName, array $fields): void
    {
        // Путь к шаблону
        $modalStubPath = app_path('Orchid/Console/Stubs/modal.php');

        // Читаем содержимое шаблона
        $modalContent = File::get($modalStubPath);

        // Генерируем строки для полей
        $fieldsString = '';
        foreach ($fields as $field) {
            $fieldsString .= "Input::make('item.{$field['code']}')->title('{$field['name']}')";
            if ($field['isRequired']) {
                $fieldsString .= "->required()";
            }
            $fieldsString .= ",\n";
        }

        // Заменяем плейсхолдеры на данные пользователя и сгенерированные поля
        $modalContent = str_replace(['ProtoModalModel', '//fields'], [$modelName . 'Modal', $fieldsString], $modalContent);

        // Путь для нового файла
        $newModalPath = app_path('Orchid/Screens/Modals/' . $modelName . 'Modal.php');

        // Создаем директорию, если она не существует
        if (!File::exists(dirname($newModalPath))) {
            File::makeDirectory(dirname($newModalPath), 0777, true);
        }

        // Сохраняем новый файл
        File::put($newModalPath, $modalContent);

        // Изменяем права доступа к файлу
        File::chmod($newModalPath, 0777);
    }

    public function generateEditScreen(string $modelName, string $screenTitle, array $fields): void
    {
        // Путь к шаблону
        $editScreenStubPath = app_path('Orchid/Console/Stubs/edit-screen.php');

        // Читаем содержимое шаблона
        $editScreenContent = File::get($editScreenStubPath);

        // Генерируем строки для полей
        $fieldsString = '';
        $rulesString = "\t\t\t'title' => ['bail', 'required', 'max:255'],\n\t\t\t'sort' => ['bail', 'required'],\n\t\t\t'code' => ['bail', 'nullable','regex:~^[A-Za-z0-9\\-_]+$~'],\n";
        $messagesString = "\t\t\t'title.required' => 'Введите код категории',\n\t\t\t'title.max' => 'Заголовок не может быть длиннее 255 символов',\n\t\t\t'sort.required' => 'Введите порядок сортировки',\n\t\t\t'code.regex' => 'В коде допустимы только цифры и латинские буквы',\n";
        foreach ($fields as $field) {
            if ($field['is_edit']) {
                $fieldsString .= "{$field['field_type']}::make('item.{$field['code']}')->title('{$field['name']}')";
                if ($field['isRequired']) {
                    $fieldsString .= "->required()";
                    $messagesString .= str_repeat(' ', 12) . "'{$field['code']}.required' => 'Введите {$field['name']}',\n";
                }
                $fieldsString .= ",\n";
                $rulesString .= str_repeat(' ', 12) . "'{$field['code']}' => ['bail', " . ($field['isRequired'] ? "'required'," : "") . " 'max:255'],\n";
                $messagesString .= str_repeat(' ', 12) . "'{$field['code']}.max' => '{$field['name']} не может быть длиннее 255 символов',\n";
            }
        }

        // Заменяем плейсхолдеры на данные пользователя и сгенерированные поля
        $editScreenContent = str_replace(['ITEM', 'DirectoryName', 'ProtoModel', 'StubEditScreen', '//..fields', 'PROTO_MODEL', '//..rules', '//..messages'], [$screenTitle, $modelName, $modelName, $modelName . 'EditScreen', $fieldsString, strtoupper(Str::snake($modelName)), $rulesString, $messagesString], $editScreenContent);

        // Путь для нового файла
        $newEditScreenPath = app_path('Orchid/Screens/' . $modelName . '/' . $modelName . 'EditScreen.php');

        // Создаем директорию, если она не существует
        if (!File::exists(dirname($newEditScreenPath))) {
            File::makeDirectory(dirname($newEditScreenPath), 0777, true);
        }

        // Сохраняем новый файл
        File::put($newEditScreenPath, $editScreenContent);

        // Изменяем права доступа к файлу
        File::chmod($newEditScreenPath, 0777);
    }

    public function generateModel(string $modelName, string $tableName, array $fields): void
    {
        // Путь к шаблону
        $modelStubPath = app_path('Orchid/Console/Stubs/model.stub');

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

        // Изменяем права доступа к файлу
        File::chmod($newModelPath, 0777);
    }

    public function updateModalValidation(string $modalName, string $tableName, array $fields): void
    {
        // Получаем путь к файлу
        $validationPath = app_path('Enums/ModalValidation.php');

        // Получаем содержимое файла
        $validationContent = File::get($validationPath);

        // Генерируем строки для полей
        $rulesString = "\t\t\t'title' => ['bail', 'required', 'max:255'],\n\t\t\t'sort' => ['bail', 'required'],\n\t\t\t'code' => ['bail', 'nullable' ,'regex:~^[A-Za-z0-9\\-_]*$~', Rule::unique('{$tableName}')->ignore(\$id)],\n";
        $messagesString = "\t\t\t'title.required' => 'Введите заголовок',\n\t\t\t'title.max' => 'Заголовок не может быть длиннее 255 символов',\n\t\t\t'sort.required' => 'Введите порядок сортировки',\n\t\t\t'code.regex' => 'В коде допустимы только цифры и латинские буквы',\n\t\t\t'code.unique' => 'Код должен быть уникальным',";
        foreach ($fields as $field) {
            if ($field['isRequired']) {
                $messagesString .= str_repeat(' ', 16) . "'{$field['code']}.required' => 'Введите {$field['name']}',\n";
            }
            $rulesString .= str_repeat(' ', 16) . "'{$field['code']}' => ['bail', " . ($field['isRequired'] ? "'required'," : "") . " 'max:255'],\n";
            $messagesString .= str_repeat(' ', 16) . "'{$field['code']}.max' => '{$field['name']} не может быть длиннее 255 символов',\n";
        }
        $caseTitle = strtoupper($modalName).'_MODAL';
        // Добавляем новый case
        $newCase = "    case {$caseTitle} = '{$modalName}Modal';\n//case-place";
        $validationContent = str_replace('//case-place', $newCase, $validationContent);

        // Добавляем новые правила
        $newRules = "            self::{$caseTitle}->value => [\n{$rulesString}            ],\n//rules-place";
        $validationContent = str_replace('//rules-place', $newRules, $validationContent);

        // Добавляем новые сообщения
        $newMessages = "            self::{$caseTitle}->value => [\n{$messagesString}            ],\n//messages-place";
        $validationContent = str_replace('//messages-place', $newMessages, $validationContent);

        // Сохраняем изменения в файле
        File::put($validationPath, $validationContent);
    }

    public function updateRoutesEnum(string $screenName, string $screenTitle): void
    {
        // Получаем путь к файлу
        $enumPath = app_path('Enums/OrchidRoutes.php');

        // Получаем содержимое файла
        $enumContent = File::get($enumPath);

        // Добавляем новый case в enum
        $newCase = "case " . strtoupper($screenName) . " = '" . strtolower($screenName) . "';\n    ";
        $enumContent = str_replace('//case-place', $newCase . '//case-place', $enumContent);

        // Добавляем новую запись в getTitle
        $newTitle = "self::" . strtoupper($screenName) . "->value => '" . $screenTitle . "',\n            ";
        $enumContent = str_replace('//title-place', $newTitle . '//title-place', $enumContent);

        // Добавляем новую запись в isSingle
        $newSingle = "self::" . strtoupper($screenName) . "->value => false,\n            ";
        $enumContent = str_replace('//single-place', $newSingle . '//single-place', $enumContent);

        // Сохраняем изменения в файле
        File::put($enumPath, $enumContent);
    }

    public function updateMenu(string $menuTitle, string $modelName): void
    {
        // Получаем путь к файлу
        $providerPath = app_path('Orchid/PlatformProvider.php');

        // Получаем содержимое файла
        $providerContent = File::get($providerPath);

        // Добавляем новый пункт меню
        $newMenu = "Menu::make('" . $menuTitle . "')->route(OrchidRoutes::" . strtoupper($modelName) . "->list())->icon(''),\n            ";
        $providerContent = str_replace('//menu-place', $newMenu . '//menu-place', $providerContent);

        // Сохраняем изменения в файле
        File::put($providerPath, $providerContent);
    }

    public function updateRoutes(string $modelName, bool $useModal = false): void
    {
        // Получаем путь к файлу
        $routesPath = base_path('routes/platform.php');

        // Получаем содержимое файла
        $routesContent = File::get($routesPath);

        // Генерируем пути к классам экранов
        $listScreenClass = "App\\Orchid\\Screens\\" . $modelName . "\\" . $modelName . "ListScreen";
        $editScreenClass = "App\\Orchid\\Screens\\" . $modelName . "\\" . $modelName . "EditScreen";

        // Добавляем новые импорты
        $newUseListScreen = "use " . $listScreenClass . ";\n";
        $routesContent = str_replace('//use-place', $newUseListScreen . '//use-place', $routesContent);

        if (!$useModal) {
            $newUseEditScreen = "use " . $editScreenClass . ";\n";
            $routesContent = str_replace('//use-place', $newUseEditScreen . '//use-place', $routesContent);
        }

        // Добавляем новый маршрут
        $newRoute = "OrchidHelper::setAdminRoutes(OrchidRoutes::" . strtoupper($modelName) . "->value, " . class_basename($listScreenClass) . "::class";
        if (!$useModal) {
            $newRoute .= ", " . class_basename($editScreenClass) . "::class";
        }
        $newRoute .= ");\n";
        $routesContent = str_replace('//route-place', $newRoute . '//route-place', $routesContent);

        // Сохраняем изменения в файле
        File::put($routesPath, $routesContent);
    }

    public function createMigration(string $modelName, array $fields): void
    {
        // Преобразуем имя модели в snake_case и добавляем суффикс "_table"
        $tableName = Str::snake(Str::plural($modelName));

        // Создаем файл миграции
        Artisan::call('make:migration', ['name' => "create_{$tableName}_table", '--create' => $tableName]);

        // Получаем путь к файлу миграции
        $migrationsPath = database_path('migrations');
        $migrationFile = collect(File::files($migrationsPath))->last();
        $migrationContent = File::get($migrationFile);

        // Remove the first occurrence of $table->id();
        $migrationContent = preg_replace('/\$table->id\(\);\s*/', '', $migrationContent, 1);

        // Генерируем код для создания столбцов
        $defaultColumnsCode = "\$table->id();\n\t\t\t\$table->boolean('is_active')->comment('Активность')->default(true);\n\t\t\t\$table->string('title')->comment('Заголовок');\n\t\t\t\$table->string('code')->comment('Код');\n\t\t\t\$table->integer('sort')->default(0)->comment('Сортировка');";
        $columnsCode = '';
        $lastFieldIndex = count($fields) - 1;
        foreach ($fields as $index => $field) {
            $columnsCode .= "\t\t\t\$table->{$field['column_type']}('{$field['code']}')->nullable()->comment('{$field['name']}');";
            if ($index !== $lastFieldIndex) {
                $columnsCode .= "\n";
            }
        }

        // Добавляем код для создания столбцов в файл миграции
        $schemaCreateLine = "Schema::create('{$tableName}', function (Blueprint \$table) {";
        $migrationContent = str_replace($schemaCreateLine, $schemaCreateLine . "\n" . $defaultColumnsCode . $columnsCode, $migrationContent);

        // Сохраняем изменения в файле миграции
        File::put($migrationFile, $migrationContent);
    }
}
