<?php

namespace App\Http\Requests;

use App\Http\Enums\ChatTypeEnum;
use App\Http\Helpers\CompanyHelper;
use App\Models\User;
use App\Rules\CountFloaNumbers;
use App\Rules\PositiveNumbers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class OfferYourPriceRequest extends FormRequest
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
            "currency_id"=>'required|integer|min:1|exists:currencies,id',
            "price"=> ['required', new PositiveNumbers(), new CountFloaNumbers()],
            "advertisement_id" => 'required|integer|digits_between:1,11|min:1',
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
            'locale' => [
                'description' => 'The locale. Default value ru.',
                'example' => "ru",
            ],
            'price' => [
                'description' => 'Price.',
                'example' => 2.33,
            ],
            'currency_id' => [
                'description' => 'Currency id. Default currency rub (id = 1).',
                'example' => 1,
            ],
            'advertisement_id' => [
                'description' => 'Advertisementd id.',
                'example' => 84,
            ],
        ];
    }
}
