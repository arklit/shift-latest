<?php

return [
    'order' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма заказа',
        'form_view' => 'forms.order-form',
        'letter_view' => 'mails.admin.order-form',
        'mail_key' => 'email',
        'info' => [
            'title' => 'Заголовок формы',
            'description' => 'Описание формы',
            'form_class' => 'form',
            'btn_text' => 'text',
            'btn_class' => 'btn',
        ],
        'form' => [
            'name' => [
                'value' => '',
                'label' => 'Имя',
                'placeholder' => 'Введите имя',
                'type' => 'text',
                'input_class' => 'field_input',
                'container_class' => 'field_container',
                'component' => 'inputComponent',
                'rules' => [
                    'required' => true,
                    'maxLength' => 10
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения',
                    'maxLength' => 'Максимальная длина 10 символов'
                ]
            ],
            'email' => [
                'value' => '',
                'label' => 'Почта',
                'placeholder' => 'Введите почту',
                'type' => 'email',
                'input_class' => 'field_input',
                'container_class' => 'field_container',
                'component' => 'inputComponent',
                'rules' => [
                    'required' => true,
                    'email' => true
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения',
                    'email' => 'Это поле должно содержать почту'
                ]
            ],
            'phone' => [
                'value' => '',
                'label' => 'Телефон',
                'placeholder' => 'Введите номер телефона',
                'type' => 'text',
                'input_class' => 'field_input',
                'container_class' => 'field_container',
                'component' => 'inputComponent',
                'rules' => [
                    'required' => true
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения'
                ]
            ],
            'select' => [
                'value' => '',
                'label' => 'Селект',
                'placeholder' => 'Выберите значение',
                'type' => 'select',
                'input_class' => 'field_select',
                'container_class' => 'field_container',
                'component' => 'select-Component',
                'rules' => [
                    'required' => true
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения'
                ]
            ],
            'fields_1' => [
                'a' => [
                    'value' => '',
                    'label' => 'a',
                    'placeholder' => 'a',
                    'type' => 'text',
                    'input_class' => 'field_input',
                    'container_class' => 'field_container',
                    'component' => 'inputComponent',
                    'rules' => [
                        'required' => true
                    ],
                    'messages' => [
                        'required' => 'Это поле обязательно для заполнения'
                    ]
                ],
                'b' => [
                    'value' => '',
                    'label' => 'b',
                    'placeholder' => 'b',
                    'type' => 'text',
                    'input_class' => 'field_input',
                    'container_class' => 'field_container',
                    'component' => 'inputComponent',
                    'rules' => [
                        'required' => true
                    ],
                    'messages' => [
                        'required' => 'Это поле обязательно для заполнения'
                    ]
                ],
            ]
        ]
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
