<?php

namespace App\Http\Requests;

use App\Http\Enums\CategoryTypeEnum;
use App\Http\Enums\PersonTypeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GetCategoryListForAdvertisementRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::in(CategoryTypeEnum::PERFORMER, CategoryTypeEnum::EMPLOYER)],
            'person_type' => ['required', 'string', Rule::in(PersonTypeEnum::PRIVATE_PERSON, PersonTypeEnum::COMPANY)],
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
