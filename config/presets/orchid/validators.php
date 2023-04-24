<?php
// Файл с правилами валидации для экранов создания/редактирования в админ панели
return [
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
            'title.required'            => 'Введите заголовок',
            'title.max'                 => 'Заголовок не может быть длиннее 120 символов',
            'category_id.required'      => 'Выберите категорию статьи',
            'description.required'      => 'Введите описание',
            'description.max'           => 'Анонс не может быть длиннее 1024 символов',
            'text.required'             => 'Введите текст статьи',
            'image_inner.required'      => 'Загрузите изображение для страницы',
            'image_outer.required'      => 'Загрузите изображение для списка',
            'publication_date.required' => 'Выберите дату публикации',
            'seo_description.max'       => 'SEO Description не может быть длиннее 1024 символов',
        ],
    ],
    'article-category' => [
        'rules' => [
            'title'                     => ['bail', 'required', 'max:120'],
            'code'                      => ['bail', 'required', 'max:160'],
            'description'               => ['bail', 'required', 'max:1024'],
            'seo_title'                 => ['bail', 'required'],
            'seo_description'           => ['bail', 'required'],
        ],
        'messages' => [
            'title.required'            => 'Введите название категории',
            'title.max'                 => 'Заголовок не может быть длиннее 120 символов',
            'code.required'             => 'Введите код категории',
            'code.max'                  => 'Заголовок не может быть длиннее 160 символов',
            'description.required'      => 'Введите описание категории',
            'description.max'           => 'Описание не может быть длиннее 160 символов',
            'seo_title'                 => 'Введите SEO заголовок',
            'seo_description'           => 'Введите SEO описание',
        ],
    ],
    'seo' => [
        'rules' => [
            'url' => ['bail', 'required', 'max:180'],
            'title' => ['bail', 'required', 'max:160'],
            'description' => ['bail', 'required',],

        ],
        'messages' => [
            'url.required' => 'Введите URL',
            'url.max' => 'URL не может быть длиннее 180 символов',
            'title.required' => 'Введите заголовок',
            'title.max' => 'Заголовок не может быть длиннее 160 символов',
            'description' => 'Введите описание',
        ],
    ],

];
