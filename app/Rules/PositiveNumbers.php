<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PositiveNumbers implements Rule
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
       return (float) $value >= 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.identifier_cannot_be_less_than_0');
    }
}
