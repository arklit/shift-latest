<?php
// Файл с правилами валидации для экранов создания/редактирования в админ панели

$codeRegex = 'regex:~^[A-z0-9\-]+$~';
$titleRegex = 'regex:~^[А-Яа-яЁёa-zA-Z\-0-9\s\'"«»]+$~u';

return [
    'defaults' => [
        'rules' => [
            'title'        => ['bail', 'required', 'max:220', $titleRegex],
            'text'         => ['bail', 'required',],
            'description'  => ['bail', 'required', 'max:164'],
            'announcement' => ['bail', 'required', 'max:164'],
            'sort'         => ['bail', 'required', 'numeric', 'min:0', 'max:9999'],
            'code'         => ['bail', 'required', 'max:30', $codeRegex],
            'slug'         => ['bail', 'required', ],
            'url'          => ['bail', 'required', 'max:250'],
            'email'        => ['bail', 'required', 'email'],
            'image'        => ['bail', 'required',],
        ],

        'messages' => [
            'title.required'        => 'Введите заголовок',
            'title.max'             => 'Заголовок не может быть длиннее 220 символов',
            'title.regex'           => 'В заголовке допустимы только кириллица, латиница и арабские цифры',
            'code.required'         => 'Введите код',
            'code.slug'             => 'Введите заголовок',
            'code.max'              => 'Код не может быть длиннее 30 символов',
            'description.required'  => 'Введите описание',
            'description.max'       => 'Описание не может быть длиннее 164 символов',
            'announcement.required' => 'Введите анонс',
            'announcement.max'      => 'Анонс не может быть длиннее 164 символов',
            'text.required'         => 'Введите основной текст',
            'sort.required'         => 'Укажите приоритет сортировки',
            'sort.numeric'          => 'Приоритет сортировки должен быть числом',
            'sort.min'              => 'Сортировка не может быть меньше нуля',
            'sort.max'              => 'Сортировка не может быть больше 9999',
            'url.required'          => 'Введите URL',
            'url.max'               => 'URL не может быть длиннее 250 символов',
            'email.required'        => 'Введите адрес электронной почты',
            'email.email'           => 'Некорректный формат электронной почты',
            'image.required'       => 'Загрузите изображение для этой записи',
        ],
    ],

    'article' => [
        'rules' => [
            'title'            => ['bail', 'required', 'max:120'],
            'category_id'      => ['bail', 'required',],
            'description'      => ['bail', 'required', 'max:1024'],
            'text'             => ['bail', 'required',],
            'image_inner'      => ['bail', 'required',],
            'image_outer'      => ['bail', 'required',],
            'publication_date' => ['bail', 'required',],
            'seo_description'  => ['bail', 'nullable', 'max:1024'],
        ],
        'messages' => [
            'title.required'            => 'Введите заголовок статьи',
            'title.max'                 => 'Заголовок статьи не может быть длиннее 120 символов',
            'category_id.required'      => 'Выберите категорию статьи',
            'description.required'      => 'Введите анонс статьи',
            'description.max'           => 'Анонс не может быть длиннее 1024 символов',
            'text.required'             => 'Введите текст статьи',
            'image_inner.required'      => 'Загрузите изображение для страницы',
            'image_outer.required'      => 'Загрузите изображение для списка',
            'publication_date.required' => 'Выберите дату публикации',
            'seo_description.max'       => 'SEO Description не может быть длиннее 1024 символов',
        ],
    ],
    'article-category' => [
        'rules'    => [
            'title'           => ['bail', 'required', 'max:120'],
            'code'            => ['bail', 'required', 'max:160'],
            'description'     => ['bail', 'required', 'max:1024'],
            'seo_title'       => ['bail', 'required'],
            'seo_description' => ['bail', 'required'],
        ],
        'messages' => [
            'title.required'           => 'Введите название категории',
            'title.max'                => 'Заголовок не может быть длиннее 120 символов',
            'code.required'            => 'Введите код категории',
            'code.max'                 => 'Заголовок не может быть длиннее 160 символов',
            'description.required'     => 'Введите описание категории',
            'description.max'          => 'Описание не может быть длиннее 160 символов',
            'seo_title.required'       => 'Введите SEO заголовок',
            'seo_description.required' => 'Введите SEO описание',
        ],
    ],
    'seo' => [
        'rules' => [
            'title'       => ['bail', 'required', 'max:160'],
            'url'         => ['bail', 'required', 'max:60'],
            'description' => ['bail', 'required',],

        ],
        'messages' => [
            'title.required'       => 'Введите заголовок',
            'title.max'            => 'Заголовок не может быть длиннее 160 символов',
            'url.required'         => 'Введите URL',
            'url.max'              => 'URL не может быть длиннее 60 символов',
            'description.required' => 'Введите описание',
        ],
    ],
];
