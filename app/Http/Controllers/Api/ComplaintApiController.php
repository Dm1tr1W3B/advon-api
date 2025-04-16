<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LanguageHelper;
use App\Http\Requests\GetComplaintTypeListRequest;
use App\Http\Requests\StoreAdvertisementAuthorComplaintRequest;
use App\Http\Requests\StoreAdvertisementComplaintRequest;
use App\Models\AdvertisementAuthorComplaint;
use App\Models\AdvertisementComplaint;
use App\Models\ComplaintType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ComplaintApiController
 * @package App\Http\Controllers\Api
 * @group Complaint
 */
class ComplaintApiController extends Controller
{

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    public function __construct(LanguageHelper $languageHelper)
    {
        $this->languageHelper = $languageHelper;

    }


    /**
     * @param GetComplaintTypeListRequest $request
     * @return JsonResponse
     *
     * @queryParam complaint_type integer required The complaint type 1-user, 2-company, 3-advertisement. Example: 1
     * @responseFile status=200 storage/responses/complain/getComplaintTypeList.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getComplaintTypeList(GetComplaintTypeListRequest $request): JsonResponse
    {
        $complaintTypes = ComplaintType::where('type', $request->complaint_type)->orderBy('display_order', 'ASC')->get(['id', 'key']);
        $keys = $complaintTypes->pluck('key')->unique()->values()->all();
        $translationNames = $this->languageHelper->getTranslations($keys, App::getLocale());

        $complaintTypes->map(function ($complaintType) use ($translationNames) {
            $complaintType->complaint_type_id =  $complaintType->id;
            $complaintType->complaint_type_name = $translationNames[$complaintType->key];

            unset($complaintType->id, $complaintType->key);
        });

        return response()->json(['data' => $complaintTypes]);
    }

    /**
     * @authenticated
     *
     * @param StoreAdvertisementComplaintRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function storeAdvertisementComplaint(StoreAdvertisementComplaintRequest $request): JsonResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $advertisementComplaint = AdvertisementComplaint::create([
                    'advertisement_id' => $request->advertisement_id,
                    'user_id' => auth()->id(),
                    'message' => '',
                ]);

                $advertisementComplaint->complaintTypes()->attach($request->complaint_type_ids);
            });

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Advertisement complaint was not stored')]], 400);
        }

        return response()->json(['data' => ['message' => 'store_advertisement_complaint']]);
    }

    /**
     * @authenticated
     *
     * @param StoreAdvertisementAuthorComplaintRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function storeAdvertisementAuthorComplaint(StoreAdvertisementAuthorComplaintRequest $request): JsonResponse
    {
        if (empty($request->author_company_id) && empty($request->author_user_id))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement author cannot be empty')]], 400);

        try {
            DB::transaction(function () use ($request) {
                $advertisementAuthorComplaint = AdvertisementAuthorComplaint::create([
                    'author_user_id' => $request->author_user_id,
                    'author_company_id' => $request->author_company_id,
                    'user_id' => auth()->id(),
                    'message' => '',
                ]);

                $advertisementAuthorComplaint->complaintTypes()->attach($request->complaint_type_ids);
            });

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Advertisement author complaint was not stored')]], 400);
        }

        return response()->json(['data' => ['message' => 'store_advertisement_author_complaint']]);

    }

}
