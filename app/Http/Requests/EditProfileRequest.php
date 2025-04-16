<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class EditProfileRequest
 * @package App\Http\Requests
 * @bodyParam name string required The name of the profile.
 * @bodyParam email string required The email of the profile. Example: 1
 * @bodyParam description string nullable The description of the profile.
 * @bodyParam phone string nullable The phone of the profile.
 * @bodyParam latitude string nullable The latitude of the profile. Example: 1
 * @bodyParam longitude string nullable The longitude of the profile. Example: 1
 * @bodyParam additional_phones object[] nullable The additional_phones of the profile. Example: 1
 * @bodyParam contacts object[] nullable The contacts of the profile. Example: 1
 * @bodyParam social_media object[] nullable The social_mediad of the profile. Example: 1
 */
class EditProfileRequest extends FormRequest
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
        /**
         * @var User
         */
        $auth = auth()->user();

        $result = [
            'name' => 'required|string|min:3|max:64',
            'description' => 'nullable|string|max:500',
            'phone' => ['nullable', 'integer', new Phone()],
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'additional_phones' => 'nullable|array',
            'additional_phones.*' => 'required|string|max:254',
            'contacts' => 'nullable|array',
            'contacts.*.id' => 'required|integer',
            'contacts.*.value' => 'required|string',
            'social_media' => 'nullable|array',
            'social_media.*.id' => 'required|integer',
            'social_media.*.value' => 'required|string',
        ];
        if ($auth)
            $result['email'] = "required|email|unique:users,email,$auth->id";

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
