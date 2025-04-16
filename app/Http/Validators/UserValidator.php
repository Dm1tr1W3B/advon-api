<?php

namespace App\Http\Validators;

use App\Rules\Phone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidatorType;


class UserValidator
{

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function insertUpdateValidator()
    {
        return Validator::make(request()->toArray(), [
            'additional_photos' => 'nullable|array|max:10',
            'additional_photos.*.image' => 'image|mimes:jpg,png',
            'phone' => ['nullable', 'integer', new Phone()],
            'email' => 'nullable|email',
        ]);
    }

}
