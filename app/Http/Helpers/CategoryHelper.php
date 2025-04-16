<?php

namespace App\Http\Helpers;

use App\Http\Enums\AdvertisementTypetEnum;
use App\Http\Enums\CategoryTypeEnum;
use App\Http\Enums\PersonTypeEnum;
use App\Models\Company;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryHelper
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    public function __construct(CategoryRepository $categoryRepository, LanguageHelper $languageHelper)
    {
        $this->categoryRepository = $categoryRepository;
        $this->languageHelper = $languageHelper;
    }

    /**
     * @param string $type
     * @return Collection
     */
    public function getCategoryList(string $type): Collection
    {
        $categories = $this->categoryRepository->getCategoryList($type);
        return $this->formatCategories($categories);
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function getCategoriesByIds(array $ids): Collection
    {
        $categories = $this->categoryRepository->getCategoriesByIds($ids);
        return $this->formatCategories($categories);
    }


    /**
     * @param string $type
     * @param string $personType
     * @return array|Collection
     */
    public function getCategoryListForAdvertisement(string $type, string $personType)
    {
        $categories = $this->categoryRepository->getCategoryList($type);

        if ($categories->isEmpty())
            return $categories;

        $exceptionCategories = [];
        //if ($type = CategoryTypeEnum::EMPLOYER) {
            $exceptionCategories[] = 'obshhestvennyj-transport';
            try {
                $user = JWTAuth::parseToken()->authenticate();

                $company = Company::where('owner_id', $user->id)->first();
                if ((!empty($company) && $personType == PersonTypeEnum::COMPANY) ||
                    ($type == AdvertisementTypetEnum::EMPLOYER && $personType == PersonTypeEnum::PRIVATE_PERSON) )
                    $exceptionCategories = [];

            } catch (JWTException $e) {

            }
        //}

        $keys = $categories->pluck('key')->unique()->values()->all();
        $translationCategories = $this->languageHelper->getTranslations($keys, App::getLocale());

        $formatCategories = [];
        $categories->each(function ($category) use ($translationCategories, $exceptionCategories, &$formatCategories) {

            if (in_array($category->key, $exceptionCategories))
                return true;

            $category->photo_url = empty($category->photo_url) ? '' : url('storage/' . $category->photo_url);
            $category->category_name = $translationCategories[$category->key];

            unset($category->key, $category->name);

            $formatCategories[] = $category;
        });

        return $formatCategories;
    }

    /**
     * @param int $categoryId
     * @return array
     */
    public function getChildCategoryList(int $categoryId): array
    {
        $childCategoryList['category'] = $this->getCategory($categoryId);
        $childCategories = $this->categoryRepository->getChildCategoryList($categoryId);

        if ($childCategories->isEmpty()) {
            $childCategoryList['child_categories'] = $childCategories;
            return $childCategoryList;
        }

        $keys = $childCategories->pluck('key')->unique()->values()->all();
        $translationChildCategories = $this->languageHelper->getTranslations($keys, App::getLocale());

        $childCategoryList['child_categories'] = $childCategories->map(function ($childCategory) use ($translationChildCategories){
            $childCategory->child_category_name = $translationChildCategories[$childCategory->key];
            unset($childCategory->key);
            return $childCategory;
        });

        return $childCategoryList;
    }

    public function getCategoryFormFields(int $categoryId, $childCategoryId = null, $isShowSmallCard = false)
    {
        $categoryFormFields = $this->categoryRepository->getFormFields($categoryId, $isShowSmallCard);

        $result = [];
        if ($categoryFormFields->isEmpty())
            return $result;

        $keys = $categoryFormFields->pluck('key')->unique()->values()->all();
        $keyTranslation = [];

        foreach ($keys as $key) {

            $key_name = $key .'_'. $categoryId;
            $key_hint = $key .'_hint_'. $categoryId;

            if ($childCategoryId) {
                $key_name .= '_' . $childCategoryId;
                $key_hint .= '_' . $childCategoryId;
            }

            $keyTranslation[] = $key_name;
            $keyTranslation[] = $key_hint;
        }

        $translationCategoryFormFields = $this->languageHelper->getTranslations($keyTranslation, App::getLocale());

        $categoryFormFields->map(function ($formField) use ($translationCategoryFormFields,
            $categoryId,
            $childCategoryId,
            &$result) {

            $formField->form_field_id =  $formField->id;
            $formField->is_show = true;

            $key_name = $formField->key .'_'. $categoryId;
            $key_hint = $formField->key .'_hint_'. $categoryId;

            if ($childCategoryId) {
                $key_name .= '_' . $childCategoryId;
                $key_hint .= '_' . $childCategoryId;
            }

            if(in_array($key_name, [
                'travel_abroad_62_64',
                'travel_abroad_62_70',
                'travel_abroad_62_65',
                'travel_abroad_62_66',
                ]))
                $formField->is_show = false;


            $formField->name = $translationCategoryFormFields[$key_name];
            $formField->hint = $translationCategoryFormFields[$key_hint];

            unset($formField->id);

            if (in_array($formField->key, ['price', 'negotiable', 'bargaining', 'payment'])) {
                $result['price_group'][$formField->key] = $formField;
                return true;
            }

            $result[$formField->key] = $formField;

        });

        return $result;
    }

    public function getCategoryFormFieldsByCategorId($categorId)
    {

        $categoryFormFields = $this->categoryRepository->getFormFields($categorId);

        $result = [];
        if ($categoryFormFields->isEmpty())
            return $result;

        $keys = $categoryFormFields->pluck('key')->unique()->values()->all();
        $keyTranslation = [];
        $exception = [
            'negotiable',
            'bargaining',
            'video',
            'link_page',
            'description',
            'hashtags',
            'sample',
            'deadline_date',
            'date_of_the',
            'date_start',
            'date_finish',
            'attendance'
        ];

        foreach ($keys as $key) {
            if (in_array($key, $exception))
                continue;

            $key_hint = $key .'_hint';
            $keyTranslation[] = $key;
            $keyTranslation[] = $key_hint;
        }

        $translationCategoryFormFields = $this->languageHelper->getTranslations($keyTranslation, App::getLocale());

        $categoryFormFields->each(function ($formField) use ($translationCategoryFormFields,
            $exception,
            &$result) {

            if (in_array($formField->key, $exception))
                return true;

            $formField->form_field_id =  $formField->id;

            $key_name = $formField->key;
            $key_hint = $formField->key .'_hint';

            $formField->name = $translationCategoryFormFields[$key_name];
            $formField->hint = $translationCategoryFormFields[$key_hint];

            unset($formField->id, $formField->category_id);

            $result[$formField->key] = $formField;

        });

        return $result;
    }

    /**
     * @param int $id
     * @param bool $isKey
     * @return Model|mixed|null
     */
    public function getCategory(int $id, bool $isKey = false)
    {
        $category = $this->categoryRepository->getCategory($id);

        if (empty($category))
            return $category;

        return $this->formatCategory($category, $isKey);
    }

    /**
     * @param string $categoryKey
     * @return mixed
     */
    public function getCategoriesByKey(string $categoryKey)
    {
        $categories = $this->categoryRepository->getCategoriesByKey($categoryKey);

        if ($categories->isEmpty())
            return $categories;

        $translationCategory = $this->languageHelper->getTranslations([$categoryKey], App::getLocale())[$categoryKey];

        return $categories->map(function ($category) use ($translationCategory) {

            $category->photo_url = empty($category->photo_url) ? '' : url('storage/' . $category->photo_url);
            $category->category_name = $translationCategory;

            return $category;

        });
    }

    public function getChildCategoriesByKey($categoryKey)
    {
        $childCategories = $this->categoryRepository->getChildCategoriesByKey($categoryKey);
        $result = [];

        if ($childCategories->isEmpty())
            return $result;

        $keys = $childCategories->pluck('key')->unique()->values()->all();
        $translationChildCategories = $this->languageHelper->getTranslations($keys, App::getLocale());

        foreach ($keys as $key) {
            $childCategory = $childCategories->where('key', $key)->first();
            $result[] = [
                'ids' => $childCategories->where('key', $key)->pluck('child_category_id')->all(),
                'child_category_name' => $translationChildCategories[$childCategory->key],
                'child_category_key' => $key,
            ];
        }

        return $result;


    }

    /**
     * @param int $id
     * @param bool $isKey
     * @return Model|null
     */
    public function getChildCategory(int $id, bool $isKey = false)
    {
        $childCategory = $this->categoryRepository->getChildCategory($id);

        if (empty($childCategory))
            return $childCategory;

        $childCategory->child_category_name =
            $this->languageHelper->getTranslations([$childCategory->key], App::getLocale())[$childCategory->key];

        if ($isKey)
            $childCategory->child_category_key = $childCategory->key;

        unset($childCategory->key);

        return $childCategory;
    }

    /**
     * @param $categories
     * @return mixed
     */
    private function formatCategories($categories)
    {
        if ($categories->isEmpty())
            return $categories;

        $keys = $categories->pluck('key')->unique()->values()->all();
        $translationCategories = $this->languageHelper->getTranslations($keys, App::getLocale());

        return $categories->map(function ($category) use ($translationCategories) {
            $category->photo_url = empty($category->photo_url) ? '' : url('storage/' . $category->photo_url);
            $category->category_name = $translationCategories[$category->key];
            $category->category_key = $category->key;

            unset($category->key, $category->name);

            return $category;
        });
    }

    /**
     * @param $category
     * @param bool $isKey
     * @return mixed
     */
    private function formatCategory($category, bool $isKey = false)
    {
        $category->photo_url = empty($category->photo_url) ? '' : url('storage/' . $category->photo_url);
        $category->category_name = $this->languageHelper->getTranslations([$category->key], App::getLocale())[$category->key];

        if ($isKey)
            $category->category_key = $category->key;

        unset($category->key);

        return $category;
    }

}
