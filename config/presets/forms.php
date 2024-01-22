<?php

return [
    'order' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма заказа',
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
                    'required' => true
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения'
                ]
            ],
            'date' => [
                'value' => '',
                'label' => 'Дата',
                'placeholder' => '__.__.____',
                'type' => 'text',
                'input_class' => 'field_date',
                'container_class' => 'field_container',
                'component' => 'datePickerComponent',
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
                'mask' => '+7 (###) ###-##-##',
                'type' => 'text',
                'input_class' => 'field_input',
                'container_class' => 'field_container',
                'component' => 'inputComponent',
                'rules' => [
                    'required' => true,
                    'minLength' => 18
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения',
                    'minLength' => 'Введите полный номер телефона'
                ]
            ],
            'select' => [
                'value' => '',
                'label' => 'Селект',
                'placeholder' => 'Выберите значение',
                'type' => 'select',
                'input_class' => 'field_select',
                'container_class' => 'field_container',
                'component' => 'selectComponent',
                'rules' => [
                    'required' => true
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения'
                ]
            ],
            'fields_1' => [
                'surname' => [
                    'value' => '',
                    'label' => 'Фамилия',
                    'placeholder' => 'Фамилия',
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
                'history' => [
                    'value' => '',
                    'label' => 'Фамилия',
                    'placeholder' => 'Фамилия',
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
                /*'docs' => [
                    'value' => '',
                    'label' => 'Документы',
                    'placeholder' => '',
                    'type' => 'text',
                    'input_class' => 'field_file',
                    'container_class' => 'field_container',
                    'component' => 'fileComponent',
                    'multiple' => false,
                    'rules' => [
                        'required' => true
                    ],
                    'messages' => [
                        'required' => 'Это поле обязательно для заполнения'
                    ]
                ],*/
            ]
        ]
    ],
    'feedback' => [
        'mail_method' => 'regularMailMethod',
        'subject' => 'Форма обратной связи"',
        'letter_view' => 'mails.admin.feedback-form',
        'mail_key' => 'reg_email',
        'info' => [
            'key' => 'feedback',
            'title' => 'форма 2',
            'description' => 'Описание формы 2',
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
                ],
                'messages' => [
                    'required' => 'Это поле обязательно для заполнения',
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
        ]
    ],
];
