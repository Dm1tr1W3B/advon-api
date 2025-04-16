<?php

namespace App\Http\Requests;

use App\Http\Enums\AdvertisementTypetEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GetAuthorAllAdvertisementsRequest extends FormRequest
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
            'advertisement_id' => 'nullable|integer|digits_between:1,11|min:1',
            'user_id' => 'nullable|integer|digits_between:1,11|min:1',
            'company_id' => 'nullable|integer|digits_between:1,11|min:1',
            'category_key' => 'nullable|string',
            'advertisement_type' => ['nullable', 'string', Rule::in(AdvertisementTypetEnum::PERFORMER, AdvertisementTypetEnum::EMPLOYER)],
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
