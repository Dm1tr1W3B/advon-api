<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFeedbackRequest extends FormRequest
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
            'email' => 'required|email|max:254',
            'name' => 'required|string|min:3|max:254',
            'feedback_type_id' => 'required|integer|digits_between:1,11|min:1',
            'message' => 'required|string',
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
            'email' => [
                'description' => 'The email.',
                'example' => 'email@email.com',
            ],
            'name' => [
                'description' => 'The author name.',
                'example' => 'Author Name',
            ],
            'feedback_type_id' => [
                'description' => 'The feedback type id.',
                'example' => '1',
            ],
            'message' => [
                'description' => 'The message.',
                'example' => 'message',
            ],
        ];
    }
}
