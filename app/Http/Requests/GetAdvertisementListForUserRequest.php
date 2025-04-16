<?php

namespace App\Http\Requests;

use App\Http\Enums\PersonTypeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GetAdvertisementListForUserRequest extends FormRequest
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
            'person_type' => ['required', 'string', Rule::in(PersonTypeEnum::PRIVATE_PERSON, PersonTypeEnum::COMPANY)],
            'is_published' =>['required', 'boolean'],
            'search' => ['nullable', 'string'],
            //'category_id' => 'nullable|integer|digits_between:1,11|min:1',
            'category_key' => 'nullable|string',
            'number_items_page' => 'nullable|integer|digits_between:1,3|min:1',
            'page' => 'nullable|integer|digits_between:1,3|min:1',

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
}
