<?php

namespace App\Http\Requests;

use App\Rules\CountFloaNumbers;
use App\Rules\PositiveNumbers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetRedirectUrlRequest extends FormRequest
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
            'payment_system_id' => 'required|integer|digits_between:1,11|min:1',
            'amount' => ['required', 'numeric', new PositiveNumbers(), new CountFloaNumbers()],
            'success_url' => 'required|string|max:254',
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
