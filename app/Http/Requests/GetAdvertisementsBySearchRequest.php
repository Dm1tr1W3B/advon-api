<?php

namespace App\Http\Requests;

use App\Http\Enums\AdvertisementTypetEnum;
use App\Http\Enums\PersonTypeEnum;
use App\Http\Enums\SearchTypeEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GetAdvertisementsBySearchRequest extends FormRequest
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

            'search' => ['nullable', 'string'],
            'category_key' => ['nullable', 'string'],
            'city_code' => ['nullable', 'string'],
            'country_code' => ['nullable', 'string'],
            'search_type' =>  ['nullable', 'string', Rule::in(SearchTypeEnum::ADVERTISEMENT, SearchTypeEnum::HASHTAGS)],

            'person_type' => ['nullable', 'string', Rule::in(PersonTypeEnum::PRIVATE_PERSON, PersonTypeEnum::COMPANY)],
            'advertisement_type' => ['nullable', 'string', Rule::in(AdvertisementTypetEnum::PERFORMER, AdvertisementTypetEnum::EMPLOYER)],
            'sort' => ['nullable', Rule::in('price_asc', 'price_desc', 'popular_asc', 'popular_desc')],

            'payment_ids' => 'nullable|array',
            'payment_ids.*' => 'nullable|integer|digits_between:1,11|min:1',
            'price_from' => 'nullable|numeric|min:0.00',
            'price_to' => 'nullable|numeric|min:0.00',
            'amount_from' => 'nullable|integer|digits_between:1,8|min:1',
            'amount_to' => 'nullable|integer|digits_between:1,8|min:1',
            'length_from' => 'nullable|integer|digits_between:1,6|min:1',
            'length_to' => 'nullable|integer|digits_between:1,6|min:1',
            'width_from_to' => 'nullable|integer|digits_between:1,6|min:1',
            'width_to' => 'nullable|integer|digits_between:1,6|min:1',
            'reach_audience_from' => 'nullable|integer|digits_between:1,8|min:1',
            'reach_audience_to' => 'nullable|integer|digits_between:1,8|min:1',

            'travel_abroad' => ['nullable', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'ready_for_political_advertising' => ['nullable', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'photo_report' => ['nullable', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'make_and_place_advertising' => ['nullable', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],


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
