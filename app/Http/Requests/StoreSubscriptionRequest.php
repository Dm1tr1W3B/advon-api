<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSubscriptionRequest extends FormRequest
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
            'sender_user_id' => 'nullable|integer|digits_between:1,11|min:1',
            'sender_company_id' => 'nullable|integer|digits_between:1,11|min:1',
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
            'sender_user_id' => [
                'description' => 'The sender user id',
                'example' => 1,
            ],
            'sender_company_id' => [
                'description' => 'The sender company id',
                'example' => 4,
            ],
        ];
    }
}
