<?php

namespace App\Http\Controllers;

use App\Models\Article;

class VueFormsController extends Controller
{
    public function getFormConfig()
    {
        $formConfig = [
            'form1' => [
                'main_info' => [
                    'key' => 'order',
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
            'form2' => [
                'main_info' => [
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
            'system' => [
                'main_info' => [
                    'key' => 'system',
                    'title' => 'форма Системы',
                    'description' => 'Описание формы Системы',
                    'form_class' => 'form system-form',
                    'btn_text' => 'Отправить',
                    'btn_class' => 'send-btn',
                ],
                'form' => [
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

        return response()->json($formConfig);
    }

    public function getOptions()
    {
        $articles = Article::query()->active()->get();
        $options = $articles->map(fn($article) => ['label' => $article->title, 'value' => $article->id]);
        return response()->json($options);
    }
}
