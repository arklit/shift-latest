<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFactory;

class AuthRequest extends AbstractRequest
{
    static function register($data): Validator
    {
        return self::throwIfErrors(ValidatorFactory::make($data,
            [
                'companyName' => 'required|string',
                'inn' => 'required|numeric',
                'activities' => 'nullable|array',
                'name' => 'required|string',
                'surname' => 'required|string',
                'patronymic' => 'required|string',
                'phone' => ['required', 'regex:/^\+?([0-9- ]{0,5})?([(|-][0-9- ]{0,6}[)|-])?[0-9- ]{5,10}$/'],
                'email' => 'required|email',
            ],
            [
                'name' => 'Поле "Имя" обязательно для заполнения',
                'surname' => 'Поле "Фамилия" обязательно для заполнения'
            ]));
    }

    static function login($data): Validator
    {
        return self::throwIfErrors(ValidatorFactory::make($data,
            [
                'login' => 'required|string',
                'password' => 'required|string',
            ]));
    }
}
