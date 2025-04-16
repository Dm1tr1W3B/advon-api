<?php

namespace App\Http\Requests;

use App\Http\Enums\AdvertisementTypetEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class GetAdvertisementListForGuestRequest extends FormRequest
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
            'advertisement_type' => ['required', 'string', Rule::in(AdvertisementTypetEnum::PERFORMER, AdvertisementTypetEnum::EMPLOYER)],
            'country_code' => ['required', 'string'],
            'city_code' => ['required', 'string'],
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
