<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\CommonHelper;
use App\Http\Helpers\LanguageHelper;
use App\Http\Helpers\SmsHelper;
use App\Http\Requests\GetBannersRequest;
use App\Http\Requests\GetSeoRequest;
use App\Http\Requests\UserCheckSmsKeyRequest;
use App\Http\Requests\userSendSmsKeyRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\Page;
use App\Models\UserPhoneKey;
use App\Repositories\CommonRepository;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class CommonApiController
 * @package App\Http\Controllers\Api
 * @group Common
 */
class CommonApiController extends Controller
{
    /**
     * @var SmsHelper
     */
    private $smsHelper;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    /**
     * @var CommonRepository
     */
    private $commonRepository;
    /**
     * @var CommonHelper
     */
    private $commonHelper;


    public function __construct(SmsHelper $smsHelper,
                                LanguageHelper $languageHelper,
                                CommonRepository $commonRepository,
                                CommonHelper $commonHelper)
    {
        $this->smsHelper = $smsHelper;
        $this->languageHelper = $languageHelper;
        $this->commonRepository = $commonRepository;
        $this->commonHelper = $commonHelper;
    }

    /**
     * @param userSendSmsKeyRequest $request
     * @return JsonResponse
     */
    public function userSendSmsKey(userSendSmsKeyRequest $request): JsonResponse
    {
        $user = auth()->user();
        try {
            $this->smsHelper->getSmsKey((int)$user->phone, $request->type);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['non_field_error' => [$e->getMessage()]], 400);
        }

        return response()->json(['data' => ['message' => 'successfully_send_sms_kye']]);
    }

    /**
     * @param UserCheckSmsKeyRequest $request
     * @return JsonResponse
     */
    public function userCheckSmsKey(UserCheckSmsKeyRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $this->smsHelper->checkSmsKey((int)$user->phone, $request->type, $request->sms_key);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['non_field_error' => [$e->getMessage()]], 400);
        }

        if ($request->type == UserPhoneKey::USER_VERIFICATION) {
            $date = new DateTime('NOW');
            $user->phone_verified_at = $date->format('Y-m-d H:i:s');
            $user->save();
        }

        $this->smsHelper->deleteSMSKey((int)$user->phone, $request->type, $request->sms_key);

        return response()->json(['data' => ['message' => 'sms_kye_is_valid']]);
    }

    public function getSMSBalance()
    {
        return response()->json(['data' => $this->smsHelper->getBalance()]);
    }

    /**
     * @return JsonResponse
     */
    public function getContacts(): JsonResponse
    {

        $contactTypes = $this->commonRepository->getContactType();

        if ($contactTypes->isEmpty())
            return $contactTypes;

        $contactTypes = $contactTypes->map(function ($contactType) {
            $contactType->photo_url = empty($contactType->photo_url) ? '' : url('storage/' . $contactType->photo_url);
            return $contactType;
        });

        return response()->json(['data' => $contactTypes]);
    }

    /**
     * @return JsonResponse
     */
    public function getSocial(): JsonResponse
    {
        $socialMediaTypes = $this->commonRepository->getSocialMediaTypes();

        if ($socialMediaTypes->isEmpty())
            return $socialMediaTypes;

        $socialMediaTypes = $socialMediaTypes->map(function ($socialMediaType) {
            $socialMediaType->photo_url = empty($socialMediaType->photo_url) ? '' : url('storage/' . $socialMediaType->photo_url);
            return $socialMediaType;
        });

        return response()->json(['data' => $socialMediaTypes]);
    }

    /**
     * @return JsonResponse
     */
    public function getPopularHashtags(): JsonResponse
    {
        return response()->json(['data' => $this->commonHelper->getPopularHashtags()]);
    }

    /**
     * @return JsonResponse
     */
    public function getPages(): JsonResponse
    {
        return response()->json(['data' => Page::get(['id', 'key'])]);
    }

    /**
     * @return JsonResponse
     *
     * @queryParam page_id integer required The page id.  Example: 1
     */
    public function getBanners(GetBannersRequest $request): JsonResponse
    {
        $banners = $this->commonRepository->getBanners($request->page_id)
            ->map(function ($banner) {
                $file = [];
                if (!empty($banner->file)) {
                    $file = json_decode($banner->file, true) [0];
                    $file['download_link'] = url('storage/' . $file['download_link']);
                }

                $banner->file = $file;

                return $banner;
            });

        return response()->json(['data' => $banners]);
    }

    /**
     * @param GetSeoRequest $request
     * @return JsonResponse
     *
     * @queryParam page_id integer required The page id.  Example: 1
     */
    public function getSeo(GetSeoRequest $request): JsonResponse
    {
        $seo = $this->commonRepository->getSeo($request->page_id, App::getLocale());
        if (empty($seo))
            $seo = [];

        return response()->json(['data' => $seo]);
    }

    /**
     * @return JsonResponse
     */
    public function getSocialLogins(): JsonResponse
    {
        $socialLogins = $this->commonRepository->getSocialLogins();

        if ($socialLogins->isEmpty()) {
            return response()->json(['data' => []]);
        }
        $socialMediaTypes = collect([]);
        $socialLogins->each(function ($socialType) use ($socialMediaTypes) {
            try {
                $socialType->image=url($socialType->image);
                $socialType->login_url = Socialite::driver($socialType->key)->stateless()->redirect()->getTargetUrl();
                $socialMediaTypes->add($socialType);

                unset($socialType->id, $socialType->active);

            } catch (\Exception $exception) {

            }
        });

        return response()->json(['data' => $socialMediaTypes]);
    }

}
