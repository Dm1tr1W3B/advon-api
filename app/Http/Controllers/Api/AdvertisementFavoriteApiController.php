<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enums\AdvertisementPaymentEnum;
use App\Http\Enums\CategoryTypeEnum;
use App\Http\Helpers\AdvertisementHelper;
use App\Http\Helpers\CategoryHelper;
use App\Http\Helpers\CommonHelper;
use App\Http\Requests\AddAdvertisementFavoriteRequest;
use App\Http\Requests\deleteAdvertisementsFavoriteRequest;
use App\Http\Requests\getAdvertisementFavoriteListRequest;
use App\Http\Requests\StoreAdvertisementRequest;
use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\Company;
use App\Models\User;
use App\Repositories\AdvertisementRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Class AdvertisementFavoriteApiController
 * @package App\Http\Controllers\Api
 * @group AdvertisementFavorite
 */
class AdvertisementFavoriteApiController extends Controller
{

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var AdvertisementRepository
     */
    private $advertisementRepository;

    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * @var AdvertisementHelper
     */
    private $advertisementHelper;

    public function __construct(CommonHelper $commonHelper,
                                AdvertisementRepository $advertisementRepository,
                                AdvertisementHelper $advertisementHelper,
                                CategoryHelper $categoryHelper)
    {
        $this->commonHelper = $commonHelper;
        $this->advertisementRepository = $advertisementRepository;
        $this->categoryHelper = $categoryHelper;
        $this->advertisementHelper = $advertisementHelper;
    }

    /**
     * @authenticated
     *
     * @param AddAdvertisementFavoriteRequest $request
     * @return JsonResponse
     * @responseFile status=200 storage/responses/advertisementFavorite/addAdvertisementFavorite.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function addAdvertisementFavorite(AddAdvertisementFavoriteRequest $request): JsonResponse
    {
        $advertisement = Advertisement::find($request->advertisement_id);
        if(empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        $advertisementFavorite = AdvertisementFavorite::where('user_id', auth()->id())
            ->where('advertisement_id', $request->advertisement_id)
            ->first();

        if(!empty($advertisementFavorite))
            return response()->json(['non_field_error' => [__('advertisement.Favorite advertisement has already been added')]], 400);

        try {
            AdvertisementFavorite::create([
                'user_id' => auth()->id(),
                'advertisement_id' => $request->advertisement_id,
            ]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Favorite advertisement was not save')]], 400);
        }

        return response()->json(['data' => ['message' => 'add_advertisement_favorite']]);
    }

    /**
     * @authenticated
     *
     * @param getAdvertisementFavoriteListRequest $request
     * @return JsonResponse
     *
     * @queryParam advertisement_type string required The advertisement type performer or employer. Example: performer
     * @queryParam search string The advertisement search hashtags or description or title. Example: "test"
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=200 storage/responses/advertisementFavorite/getAdvertisementFavoriteList.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getAdvertisementFavoriteList(getAdvertisementFavoriteListRequest $request): JsonResponse
    {


        $date = $this->commonHelper->getInvertMonth();
        $advertisementFavorites = $this->advertisementRepository
            ->getAdvertisementFavoriteList(auth()->id(), $request->advertisement_type, $date, $request->search);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $query = [
            'advertisement_type' => $request->advertisement_type,
            'search' => $request->search
        ];

        $paginator = $this->commonHelper->paginate($advertisementFavorites, $prePage, $request->page, ['path' => url('/api/v1/getAdvertisementFavoriteList'), 'query' => $query ]);

        return response()->json(['data' => $paginator->setCollection($this->advertisementHelper->getSmallCardFormat($paginator->getCollection()))]);
    }

    /**
     * @authenticated
     *
     * @param deleteAdvertisementsFavoriteRequest $request
     * @return JsonResponse
     *
     * @responseFile status=200 storage/responses/advertisementFavorite/deleteAdvertisementsFavorite.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function deleteAdvertisementsFavorite(deleteAdvertisementsFavoriteRequest $request)
    {
        try {
            $this->advertisementRepository->deleteAdvertisementsFavorite($request->advertisement_ids, auth()->id());
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Favorite advertisement was not delete')]], 400);
        }

        return response()->json(['data' => ['message' => 'favorite_advertisements_was_deleted']]);
    }
}
