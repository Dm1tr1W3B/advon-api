<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetAdvertisementCommentListRequest;
use App\Http\Requests\StoreAdvertisementCommentRequest;
use App\Http\Resources\GetAdvertisementCommentListResource;
use App\Mail\CommentAdvertisementEmail;
use App\Models\Advertisement;
use App\Models\AdvertisementComment;
use App\Models\User;
use App\Models\UserSetting;
use App\Repositories\AdvertisementRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Class AdvertisementCommentApiController
 * @package App\Http\Controllers\Api
 * @group AdvertisementComment
 */
class AdvertisementCommentApiController extends Controller
{

    /**
     * @var AdvertisementRepository
     */
    private $advertisementRepository;

    public function __construct(AdvertisementRepository $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    /**
     * @param GetAdvertisementCommentListRequest $request
     * @return JsonResponse
     */
    public function getAdvertisementCommentList(GetAdvertisementCommentListRequest $request): JsonResponse
    {
        return GetAdvertisementCommentListResource::collection(
            $this->advertisementRepository->getAdvertisementComments( $request->advertisement_id)
        )->response();
    }

    /**
     * @param StoreAdvertisementCommentRequest $request
     * @return JsonResponse
     */
    public function storeAdvertisementComment(StoreAdvertisementCommentRequest $request): JsonResponse
    {
        $user = auth()->user();

        if (empty($user->email_verified_at))
            return response()->json(['non_field_error' => [__('auth.The user email address is not verified')]], 400);

        $advertisement = Advertisement::find($request->advertisement_id);
        if(empty($advertisement))
            return response()->json(['non_field_error' => [__('advertisement.Advertisement not found')]], 400);

        try {
            AdvertisementComment::create([
                'advertisement_id' => $request->advertisement_id,
                'user_id' => $user->id,
                'message' => $request->message,
            ]);

            $userSetting = UserSetting::where('user_id', $advertisement->user_id)->first();
            if (!empty($userSetting)) {
                $userAdvertisement = User::find($advertisement->user_id);
                try {
                    if ($userSetting->is_receive_comments_by_email) {
                        Mail::to($userAdvertisement->email)->send(new CommentAdvertisementEmail($advertisement, $userAdvertisement, $userAdvertisement->email));
                    }

                    if ($userSetting->is_receive_comments_by_phone) {
                        // todo
                    }

                } catch (\Throwable $throwable) {
                    Log::error($throwable->getMessage());
                }

            }

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Advertisement comment was not save')]], 400);
        }

        return response()->json(['data' => ['message' => 'add_advertisement_comment']]);
    }
}
