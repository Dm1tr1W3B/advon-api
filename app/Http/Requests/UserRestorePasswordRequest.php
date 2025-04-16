<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRestorePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required|string|max:254',
            'password' => ['required', 'string', 'min:6', 'confirmed', 'regex:/^[A-Za-z0-9]+$/'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            $validator->errors(), 422));
    }

    /**
     * @return array[]
     */
    public function bodyParameters()
    {
        return [
            'locale' => [
                'description' => 'The locale. Default value ru.',
                'example' => "ru",
            ],
            'token' => [
                'description' => 'The token.',
                'example' => 'string',
            ],
            'password' => [
                'description' => 'The password.',
                'example' => '123456as',
            ],
            'password_confirmation' => [
                'description' => 'The password confirmation.',
                'example' => '123456as',
            ],

        ];
    }
}
