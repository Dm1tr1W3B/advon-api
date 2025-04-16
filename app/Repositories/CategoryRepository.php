<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\FormField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;


class CategoryRepository
{


    /**
     * @param string $type
     * @return Collection
     */
    public function getCategoryList(string $type): Collection
    {
        return Category::leftjoin('front_variables_lang as fvl', 'fvl.key', '=', 'categories.key')
            ->leftjoin('images as i', 'i.id', '=', 'categories.image_id')
            ->select(
                'categories.id as category_id',
                'categories.key as key',
                'categories.name as name',
                'i.photo_url'
            )
            ->where('categories.type', $type)
            ->orderBy('categories.num_left', 'asc')
            ->get();
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function getCategoriesByIds(array $ids): Collection
    {
        return Category::leftjoin('front_variables_lang as fvl', 'fvl.key', '=', 'categories.key')
            ->leftjoin('images as i', 'i.id', '=', 'categories.image_id')
            ->select(
                'categories.id as category_id',
                'categories.key as key',
                'categories.name as name',
                'i.photo_url'
            )
            ->whereIn('categories.id', $ids)
            ->orderBy('categories.num_left', 'asc')
            ->get();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function getCategory(int $id):? Model
    {
        return Category::leftjoin('images as i', 'i.id', '=', 'categories.image_id')
            ->select(
                'categories.id as category_id',
                'categories.key as key',
                'i.photo_url'
            )
            ->where('categories.id', $id)
            ->first();
    }

    /**
     * @param int $categoryId
     * @return Collection
     */
    public function getChildCategoryList(int $categoryId): Collection
    {
        return ChildCategory::select('id as child_category_id', 'key')
            ->where('child_categories.category_id', $categoryId)
            ->orderBy('child_categories.num_left', 'asc')
            ->get();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function getChildCategory(int $id):? Model
    {
        return ChildCategory::select('id as child_category_id', 'key')
            ->where('child_categories.id', $id)
            ->first();
    }

    /**
     * @param int $categoryId
     * @param false $isShowSmallCard
     * @return Collection
     */
    public function getFormFields(int $categoryId, $isShowSmallCard = false): Collection
    {
        $formFields = FormField::leftjoin('category_form_field as cff', 'cff.form_field_id', '=', 'form_fields.id')
            ->select('form_fields.id as id', 'form_fields.key as key', 'form_fields.type as type')
            ->where('cff.category_id', $categoryId);

        if ($isShowSmallCard)
            $formFields = $formFields->where('cff.is_show_small_card', $isShowSmallCard);

        return $formFields->get();
    }

    /**
     * @param string $categoryKey
     * @return mixed
     */
    public function getCategoriesByKey(string $categoryKey)
    {
        return Category::leftjoin('images as i', 'i.id', '=', 'categories.image_id')
            ->select(
                'categories.id as category_id',
                'categories.key as key',
                'categories.title_ograph as title_ograph',
                'i.photo_url',
                'categories.type as type'
            )
            ->where('categories.key', $categoryKey)
            ->get();
    }

    /**
     * @param string $categoryKey
     * @return Collection
     */
    public function getChildCategoriesByKey(string $categoryKey): Collection
    {
        return ChildCategory::join('categories as c', 'c.id', '=', 'child_categories.category_id')
            ->select('child_categories.id as child_category_id', 'child_categories.key')
            ->where('c.key', $categoryKey)
            ->orderBy('child_categories.num_left', 'asc')
            ->get();
    }



}
