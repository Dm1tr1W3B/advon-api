<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TurnBlockChatRequest extends FormRequest
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
            "chat_id" => "required|exists:chats,id|integer|min:1",
            'is_blocked' => ['required', 'boolean'],
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
     * @return \string[][]
     */
    public function bodyParameters()
    {
        return [
            'chat_id' => [
                'description' => 'Contents of the post',
            ],
            'is_blocked' => [
                'description' => 'Contents of the post',
            ],
        ];
    }
}
