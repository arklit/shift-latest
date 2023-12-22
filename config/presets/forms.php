<?php

return [
    'test' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма "Просчет за 15 минут"',
        'form_view' => 'forms.test-form',
        'letter_view' => 'mails.admin.test-form',
        'mail_key' => 'email',
        'rules' => [
            'name' => ['bail', 'required'],
            'email' => ['bail', 'required'],
        ],
        'messages' => [
            'name.required' => 'Поле "Имя" обязательно для заполнения',
            'email.required' => 'Поле "Email" обязательно для заполнения',
        ],
    ],
    'test2' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма "Просчет за 15 минут"',
        'form_view' => 'forms.test-form',
        'letter_view' => 'mails.admin.test-form',
        'mail_key' => 'reg_email',
        'rules' => [
            'name' => ['bail', 'required'],
            'email' => ['bail', 'required'],
        ],
        'messages' => [
            'name.required' => 'Поле "Имя" обязательно для заполнения',
            'email.required' => 'Поле "Email" обязательно для заполнения',
        ],
    ],
];
