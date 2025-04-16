<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AdvertisementHelper;
use App\Http\Requests\DeleteSubscriptionRequest;
use App\Http\Requests\GetAdvertisementCommentListRequest;
use App\Http\Requests\GetSubscriptionListRequest;
use App\Http\Requests\StoreAdvertisementCommentRequest;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Resources\GetAdvertisementCommentListResource;
use App\Models\Advertisement;
use App\Models\AdvertisementComment;
use App\Models\AdvertisementFavorite;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSetting;
use App\Repositories\AdvertisementRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class AdvertisementCommentApiController
 * @package App\Http\Controllers\Api
 * @group Subscription
 */
class SubscriptionApiController extends Controller
{


    /**
     * @var AdvertisementHelper
     */
    private $advertisementHelper;

    public function __construct(AdvertisementHelper $advertisementHelper)
    {
        $this->advertisementHelper = $advertisementHelper;

    }


    /**
     * @authenticated
     *
     * @param GetSubscriptionListRequest $request
     * @return JsonResponse
     *
     * @queryParam person_type string required The person type private_person or company. Example: private_person
     * @responseFile status=200 storage/responses/subscription/getSubscriptionList.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getSubscriptionList(GetSubscriptionListRequest $request): JsonResponse
    {
        return response()->json(['data' => $this->advertisementHelper->getSubscriptionList(auth()->id(), $request->person_type)]);
    }

    /**
     * Метод подписки на сайте для зарегистрированных пользователей
     *
     * @authenticated
     *
     * @param StoreSubscriptionRequest $request
     * @return JsonResponse
     * @responseFile status=200 storage/responses/subscription/storeSubscription.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     *
     */
    public function storeSubscription(StoreSubscriptionRequest $request): JsonResponse
    {
        if (empty($request->sender_user_id) && empty($request->sender_company_id))
            return response()->json(['non_field_error' => [__('advertisement.sender user id and sender company id cannot be empty')]], 400);

        $recipientUserId = auth()->id();

        $subscription = Subscription::where('recipient_user_id', $recipientUserId);

        if (!empty($request->sender_user_id)) {
            if ($request->sender_user_id == $recipientUserId)
                return response()->json(['non_field_error' => [__('advertisement.You cannot subscribe to yourself')]], 400);

            $subscription = $subscription->where('sender_user_id', $request->sender_user_id);
        }


        if (!empty($request->sender_company_id)) {
            $company = Company::where('owner_id', $recipientUserId)->first();
            if (!empty($company) && $company->id == $request->sender_company_id)
                return response()->json(['non_field_error' => [__('advertisement.You cannot subscribe to your company')]], 400);

            $subscription = $subscription->where('sender_company_id', $request->sender_company_id);
        }


        $subscription = $subscription->first();

        if (!empty($subscription))
            return response()->json(['non_field_error' => [__('advertisement.You are already subscribed')]], 400);

        try {
            Subscription::create([
                'recipient_user_id' => $recipientUserId,
                'advertisement_id' => null,
                'sender_user_id' => $request->sender_user_id,
                'sender_company_id' => $request->sender_company_id,
            ]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.The subscription comment was not save')]], 400);
        }

        return response()->json(['data' => ['message' => 'add_subscription']]);
    }

    /**
     * Метод отписки на сайте для зарегистрированных пользователей
     *
     * @authenticated
     *
     * @param StoreSubscriptionRequest $request
     * @return JsonResponse
     * @responseFile status=200 storage/responses/subscription/storeSubscription.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     *
     */
    public function deleteSubscriptionForUser(StoreSubscriptionRequest $request): JsonResponse
    {
        if (empty($request->sender_user_id) && empty($request->sender_company_id))
            return response()->json(['non_field_error' => [__('advertisement.sender user id and sender company id cannot be empty')]], 422);

        $recipientUserId = auth()->id();
        $subscription = Subscription::where('recipient_user_id', $recipientUserId);

        if (!empty($request->sender_user_id))
            $subscription = $subscription->where('sender_user_id', $request->sender_user_id);

        if (!empty($request->sender_company_id))
            $subscription = $subscription->where('sender_company_id', $request->sender_company_id);

        $subscription = $subscription->first();

        if (empty($subscription))
            return response()->json(['data' => ['message' => 'subscription_was_deleted']]);

        $subscription->delete();

        return response()->json(['data' => ['message' => 'subscription_was_deleted']]);

    }

    /**
     * @authenticated
     *
     * @param DeleteSubscriptionRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function deleteSubscription(DeleteSubscriptionRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Subscription::whereIn('id', $request->subscription_ids)
                    ->delete();
            });
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('advertisement.Subscription was not delete')]], 400);
        }

        return response()->json(['data' => ['message' => 'subscription_was_deleted']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteSubscriptionByEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user))
            return redirect(env('FRONT_URL', 'https://advon.test.ut.in.ua'));

        try {
            DB::transaction(function () use ($user) {
                UserSetting::where('user_id', $user->id)
                    ->update([
                        'is_hide_user' => false,
                        'is_hide_company' => false,
                        'is_receive_news' => false,
                        'is_receive_messages_by_email' => false,
                        'is_receive_comments_by_email' => false,
                        'is_receive_price_favorite_by_email' => false,
                        'is_receive_messages_by_phone' => false,
                        'is_receive_comments_by_phone' => false,
                        'is_receive_price_favorite_by_phone' => false,
                    ]);

                Subscription::where('recipient_user_id', $user->id)
                    ->delete();
            });
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return redirect(env('FRONT_URL', 'https://advon.test.ut.in.ua'));
        }

        return redirect(env('FRONT_URL', 'https://advon.test.ut.in.ua'));
    }
}
