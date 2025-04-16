<?php

namespace App\Http\Requests;

use App\Rules\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCompanyRequest extends FormRequest
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
        $result = [
            'name' => 'required|string|min:3|max:64',
            'logo' => 'nullable|image|mimes:jpg,png',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,png',
            'description' => 'nullable|string|max:500',
            'site_url' => 'nullable|url',
            'hashtags' => 'nullable|string|max:254',
            'video_url' => 'nullable|string|max:254',
            'audio' => 'nullable|mimes:mpga,wav,mp3|max:15360',//15mb
            'document' => 'nullable|file|max:5120',//5mb
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => ['nullable', 'integer', new Phone()],
            'additional_phones' => 'nullable|array',
            'additional_phones.*' => 'required|string|max:254',
            'email' => 'nullable|email',
            'contacts' => 'nullable|array',
            'contacts.*.id' => 'required|integer',
            'contacts.*.value' => 'required|string',
            'social_media' => 'nullable|array',
            'social_media.*.id' => 'required|integer',
            'social_media.*.value' => 'required|string',
        ];

        return $result;
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


}
