<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateAdvertisementRequest extends FormRequest
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
            'advertisement_id' => 'required|integer|digits_between:1,11|min:1',
            'title' => 'required|string|max:254',
            'currency_id' => 'nullable|integer|digits_between:1,11|min:1',
            'video_url' => 'nullable|string|max:254',
            'is_published' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'is_hide' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'country'  => 'nullable|string|max:254',
            'region'  => 'nullable|string|max:254',
            'city'  => 'nullable|string|max:254',
            'country_ext_code'  => 'nullable|string|max:254',
            'region_ext_code'  => 'nullable|string|max:254',
            'city_ext_code'  => 'nullable|string|max:254',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,png',
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
            'advertisement_id' => [
                'description' => 'The advertisement id.',
                'example' => 1,
            ],
            'title' => [
                'description' => 'The title.',
                'example' => 'The title',
            ],
            'description' => [
                'description' => 'The description.',
                'example' => 'The description',
            ],
            'currency_id' => [
                'description' => 'Currency id. Default currency rub (id = 1).',
                'example' => 1,
            ],
            'is_hide' => [
                'description' => 'is hide',
                'example' => true,
            ],
        ];
    }
}
