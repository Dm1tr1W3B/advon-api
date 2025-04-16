<?php

namespace App\Http\Requests;

use App\Rules\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

/**
 * Class StoreUserRequest
 * @package App\Http\Requests
 * @bodyParam password_confirmation required The confirmation of the password
 */
class StoreUserRequest extends FormRequest
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
            'phone' => ['nullable', 'integer', new Phone()],
            'email' => ['required', 'string', 'email', 'unique:users', 'max:254'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^[A-Za-z0-9]+$/'],
            'is_agree' => ['required', 'boolean', Rule::in(true)],
            'ref_code' => ['nullable', 'string'],

        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @param Validator $validator
     * @return void
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
            'email' => [
                'description' => 'The user email.',
                'example' => 'email@email.com',
            ],
            'password' => [
                'description' => 'The user password.',
                'example' => '1234568',
            ],
            'is_agree' => [
                'description' => 'The agree.',
                'example' => true,
            ],
            'ref_code' => [
                'description' => 'The ref_code.',
                'example' => '1234',
            ],
        ];
    }
}
