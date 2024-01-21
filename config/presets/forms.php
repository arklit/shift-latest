<?php

return [
    'order' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма заказа',
        'form_view' => 'forms.order-form',
        'letter_view' => 'mails.admin.order-form',
        'mail_key' => 'email',
        'rules' => [
            'name' => ['bail', 'required'],
            'email' => ['bail', 'email', 'required'],
            'phone' => ['bail', 'required'],
            'select' => ['bail', 'required'],
            'a' => ['bail', 'required'],
            'b' => ['bail', 'required'],
        ],
        'messages' => [
            'name.required' => 'Поле "Имя" обязательно для заполнения',
            'email.required' => 'Поле "Email" обязательно для заполнения',
            'phone.required' => 'Поле "Телефон" обязательно для заполнения',
            'select.required' => 'Поле "Компания" обязательно для заполнения',
            'a.required' => 'Поле "a" обязательно для заполнения',
            'b.required' => 'Поле "b" обязательно для заполнения',
        ],
    ],
    'feedback' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма обратной связи"',
        'form_view' => 'forms.feedback-form',
        'letter_view' => 'mails.admin.feedback-form',
        'mail_key' => 'reg_email',
        'rules' => [
            'name' => ['bail', 'required'],
            'phone' => ['bail', 'required'],
        ],
        'messages' => [
            'name.required' => 'Поле "Имя" обязательно для заполнения',
            'phone.required' => 'Поле "Email" обязательно для заполнения',
        ],
    ],
];
