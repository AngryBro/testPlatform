<?php

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class JsonValidator {
    static function validate($request) {
        $validator = Validator::make(
            $request->all(),
            [
                'json' => 'json',
            ],
            [
                'json.json' => 'Невалидная строка JSON',
            ]
        );

        if($validator->fails()) {
            return ['fails' => true, 'errors' => $validator->errors()->toArray()];
        }
        else {
            $data = $validator->validated();
            return ['fails' => false, 'json' => $data['json']];
        }
    }
}