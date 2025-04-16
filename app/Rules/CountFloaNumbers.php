<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CountFloaNumbers implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $valueArray = explode (".", (float) $value);

		if (strlen($valueArray[0]) > 8 || (!empty($valueArray[1]) && strlen($valueArray[1]) > 2))
			return false;

		return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.invalid_value');
    }
}
