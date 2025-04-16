<?php

namespace App\Http\Requests;

use App\Http\Helpers\CompanyHelper;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendMessageRequest extends FormRequest
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
            "chat_id" => "exists:chats,id|nullable|integer|min:1",
            // "from_id" => "exists:users,id|nullable|integer|min:1",
            "to_id" => "exists:users,id|nullable|integer|min:1",
            "to_company_id" => "exists:companies,id|nullable|integer|min:1",
            // "from_company_id" => "exists:companies,id|nullable|integer|min:1",
            "text" => "nullable|max:500",
            "files.*" => "nullable|mimes:jpg,jpeg,png,pdf|max:20000'",
            "advertisement_id" => "nullable|integer|min:1"
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->text && !$this->files->count()) {
                $validator->errors()->add('text', __("chat.Message cant be empty"));
            }

        });
        return;
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
            'chat_id' => [
                'description' => 'Chat id.',
                'example' => 1,
            ],
            /*
            'from_id' => [
                'description' => 'From user id. ',
                'example' => 1,
            ],
            */
            'to_id' => [
                'description' => 'To user id',
                'example' => 1,
            ],
            'to_company_id' => [
                'description' => 'To company id.',
                'example' => 1,
            ],
            /*
            'from_company_id' => [
                'description' => 'From company id.',
                'example' => 1,
            ],
            */
            'text' => [
                'description' => 'Message text.',
                'example' => 1,
            ],
            'files' => [
                'description' => 'Message files. Array. File types jpg,jpeg,png,pdf',
                'example' => 'any files',
            ],
            'advertisement_id' => [
                'description' => 'Advertisementd id.',
                'example' => 84,
            ],
        ];
    }
}
