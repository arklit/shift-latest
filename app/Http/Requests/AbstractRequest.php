<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class AbstractRequest {
    static function throwIfErrors($validator): Validator
    {
        if ($validator->errors()->all()) {
            throw new ValidationException($validator);
        }
        return $validator;
    }
}
