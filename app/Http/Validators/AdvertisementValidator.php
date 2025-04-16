<?php

namespace App\Http\Validators;

use App\Models\ChildCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidatorType;


class AdvertisementValidator
{
    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function storeAdvertisementValidator(array $formFieldKeys): \Illuminate\Contracts\Validation\Validator
    {
        $formRule = [
            'price' => 'required|numeric|min:0.00',
            'bargaining' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')], // торг
            'negotiable' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')], // Договорная
            'payment' => ['required', Rule::in(1,2,3,4,5,6)],
            'hashtags' => 'required|string',
            'reach_audience' => 'required|integer|digits_between:1,8|min:1',
            'travel_abroad' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'ready_for_political_advertising' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'photo_report' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'make_and_place_advertising' => ['required', Rule::in(true, false, 'true', 'false', 1, 0, '1', '0')],
            'amount' => 'required|integer|digits_between:1,8|min:1',
            'length' => 'required|integer|digits_between:1,6|min:1',
            'width' => 'required|integer|digits_between:1,6|min:1',
            'video' => 'required|string|max:254',
            'deadline_date' => 'required|integer|min:1',
            'sample' => 'required|mimes:mp3',
            'link_page' => 'required|string|max:254',
            'date_of_the' => 'required|integer|min:1',
            'date_start' => 'required|integer|min:1',
            'date_finish' => 'required|integer|min:1',
            'description' => 'required|string',
        ];

        $rules = [];

        foreach ($formFieldKeys as $formFieldKey) {
            if (!empty($formRule[$formFieldKey]))
                $rules[$formFieldKey] = $formRule[$formFieldKey];
        }

        return Validator::make(request()->toArray(), $rules);
    }

    public function checkChildCategoryId(int $categoryId, $childCategoryId = null)
    {
        $childCategories = ChildCategory::where('category_id', $categoryId)->get();
        if ($childCategories->isEmpty() && $childCategoryId == null)
            return true;

        $childCategory = $childCategories->where('id', $childCategoryId)->first();
        if (!empty($childCategory))
            return true;

        return false;
    }

}
