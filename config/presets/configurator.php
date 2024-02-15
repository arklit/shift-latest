<?php

return [
    [
        'title' => 'Настройки',
        'group' => [
            'count' => 2,
            'fields' => [
                'address' => [
                    'title' => 'Адрес',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'schedule' => [
                    'title' => 'График работы',
                    'type' => \Orchid\Screen\Fields\TextArea::class,
                    'required' => true,
                ],
                'phone' => [
                    'title' => 'Номер телефона',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'mask' => '+7 (999) 999-99-99',
                    'required' => true,
                ],
                'email_contacts' => [
                    'title' => 'E-mail для связи',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'email_forms' => [
                    'title' => 'E-mail для получения заявок из форм',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'email_orders' => [
                    'title' => 'E-mail для получения заказов',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'coordinates' => [
                    'title' => 'Координаты',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'pre_banner_title' => [
                    'title' => 'Заголовок над главным баннером',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'pre_banner_link' => [
                    'title' => 'Ссылка над главным баннером',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'banner_title' => [
                    'title' => 'Заголовок для главного баннера',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'banner_link' => [
                    'title' => 'Ссылка для главного баннера',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'vk' => [
                    'title' => 'Ссылка на VK',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'tg' => [
                    'title' => 'Ссылка на Telegram',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'required' => true,
                ],
                'wp' => [
                    'title' => 'Номер телефона WhatsApp',
                    'type' => \Orchid\Screen\Fields\Input::class,
                    'mask' => '+7 (999) 999-99-99',
                    'required' => true,
                ],
                'banner_image' => [
                    'title' => 'Изображение для главного баннера',
                    'type' => \App\Orchid\Fields\Cropper::class,
                    'required' => true,
                ],
            ],
        ],
    ],
    [
        'title' => 'Описания',
        'group' => [
            'count' => 2,
            'fields' => [
                'pre_banner_description' => [
                    'title' => 'Описание над главным баннером',
                    'type' => \App\Orchid\Fields\TinyMce::class,
                    'required' => true,
                ],

                'banner_description' => [
                    'title' => 'Описание для главного баннера',
                    'type' => \App\Orchid\Fields\TinyMce::class,
                    'required' => true,
                ],

                'banner_video' => [
                    'title' => 'Видео для главного баннера',
                    'type' => \Orchid\Screen\Fields\Upload::class,
                    'required' => false,
                ],
                'text_about' => [
                    'title' => 'Текст о нас',
                    'type' => \App\Orchid\Fields\TinyMce::class,
                    'required' => true,
                ],
            ],
        ],
    ],
    [
        'title' => 'Скрипты',
        'group' => [
            'count' => 2,
            'fields' => [
                'head' => [
                    'title' => 'Скрипты в head',
                    'type' => \Orchid\Screen\Fields\Code::class,
                    'required' => false,
                ],
                'body_start' => [
                    'title' => 'Скрипты в начало body',
                    'type' => \Orchid\Screen\Fields\Code::class,
                    'required' => false,
                ],
                'body_end' => [
                    'title' => 'Скрипты в конец body',
                    'type' => \Orchid\Screen\Fields\Code::class,
                    'required' => false,
                ]
            ],
        ],
    ],
];
