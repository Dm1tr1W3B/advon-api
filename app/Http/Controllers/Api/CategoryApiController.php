<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\CategoryHelper;
use App\Http\Requests\GetCategoryFormFieldsRequest;
use App\Http\Requests\GetCategoryListForAdvertisementRequest;
use App\Http\Requests\GetCategoryListRequest;
use App\Http\Requests\GetChildCategoryListRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


/**
 * Class CategoryApiController
 * @package App\Http\Controllers\Api
 * @group Category
 */
class CategoryApiController extends Controller
{
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    public function __construct(CategoryHelper $categoryHelper)
    {
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * @param GetCategoryListRequest $request
     * @return JsonResponse
     *
     * @queryParam type string  required The category type performer or employer. Example: 'employer'
     * @responseFile status=200 storage/responses/category/getCategoryList.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getCategoryList(GetCategoryListRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->categoryHelper->getCategoryList($request->type)]);
    }

    /**
     * @authenticated
     *
     * @param GetCategoryListForAdvertisementRequest $request
     * @return JsonResponse
     *
     * @queryParam type string  required The category type performer or employer. Example: 'employer'
     * @queryParam person_type string  required The person type private_person or company. Example: 'company'
     * @responseFile status=200 storage/responses/category/getCategoryList.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getCategoryListForAdvertisement(GetCategoryListForAdvertisementRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->categoryHelper->getCategoryListForAdvertisement($request->type, $request->person_type)]);
    }

    /**
     * @param GetChildCategoryListRequest $request
     * @return JsonResponse
     *
     * @queryParam category_id integer required The category id. Example: 7
     * @responseFile status=200 storage/responses/category/getChildCategoryList.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getChildCategoryList(GetChildCategoryListRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->categoryHelper->getChildCategoryList($request->category_id)]);
    }

    /**
     * @param GetCategoryFormFieldsRequest $request
     * @return JsonResponse
     *
     * @queryParam category_id integer required The category id. Example: 7
     * @queryParam child_category_id integer The category id. Example: 10
     * @responseFile status=200 storage/responses/category/getCategoryFormFields.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getCategoryFormFields(GetCategoryFormFieldsRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->categoryHelper->getCategoryFormFields($request->category_id, $request->child_category_id, false)]);
    }


}
