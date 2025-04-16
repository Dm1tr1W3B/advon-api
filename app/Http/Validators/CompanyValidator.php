<?php

namespace App\Http\Validators;

use App\Rules\Phone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidatorType;


class CompanyValidator
{

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function insertUpdateValidator()
    {
        return Validator::make(request()->toArray(), [
            'owner_id' =>'required|integer|digits_between:1,11|min:1',
            'name' => 'required|string|min:3|max:64',
            'logo_id' => 'nullable|image|mimes:jpg,png',
            'additional_photos' => 'nullable|array|max:10',
            'additional_photos.*.image' => 'image|mimes:jpg,png',
            'description' => 'nullable|string|max:500',
            'site_url' => 'nullable|url',
            'hashtags' => 'nullable|string|max:254',
            'video_url' => 'nullable|url',
            'audio_url' => 'nullable|mimes:mpga,wav,mp3|max:15360',//15mb
            'document_url' => 'nullable|file|max:5120',//5mb
            'phone' => ['nullable', 'integer', new Phone()],
            'email' => 'nullable|email',
        ]);
    }

}
