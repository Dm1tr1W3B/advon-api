<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Http\Helpers\AdvertisementHelper;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\CommonHelper;
use App\Http\Helpers\GeoDBHelper;
use App\Http\Helpers\TransactionBalanceHelper;
use App\Http\Helpers\UserHelper;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CheckEmailVerificationCodeRequest;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\GetUserForGuestRequest;
use App\Http\Requests\IdRequest;
use App\Http\Requests\SetHideCompany;
use App\Http\Requests\SetHideUser;
use App\Http\Requests\SetReceiveCommentsByEmail;
use App\Http\Requests\SetReceiveCommentsByPhone;
use App\Http\Requests\SetReceiveMessagesByEmail;
use App\Http\Requests\SetReceiveMessagesByPhone;
use App\Http\Requests\SetReceiveNews;
use App\Http\Requests\SetReceivePriceFavoriteByEmail;
use App\Http\Requests\SetReceivePriceFavoriteByPhone;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\TransferBonusToBalanceRequest;
use App\Http\Requests\UpdatePhoneRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Requests\UploadImagesRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserRestorePasswordRequest;
use App\Http\Resources\ImageResource;
use App\Http\Resources\RefResource;
use App\Http\Resources\UserResource;
use App\Models\Advertisement;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Models\UserContact;
use App\Models\UserPhone;
use App\Models\UserSetting;
use App\Models\UserSocial;
use App\Repositories\UserRepository;
use DateTime;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use TCG\Voyager\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class UserApiController
 * @package App\Http\Controllers\Api
 * @group User
 */
class UserApiController extends Controller
{
    /**
     * @var int
     */
    private $codeLength = 6;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserHelper
     */
    private $userHelper;

    /**
     * @var AuthHelper
     */
    private $authHelper;
    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var TransactionBalanceHelper
     */
    private $transactionBalanceHelper;

    /**
     * @var AdvertisementHelper
     */
    private $advertisementHelper;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    public function __construct(UserRepository           $userRepository,
                                UserHelper               $userHelper,
                                AuthHelper               $authHelper,
                                GeoDBHelper              $GeoDBHelper,
                                TransactionBalanceHelper $transactionBalanceHelper,
                                AdvertisementHelper $advertisementHelper,
                                CommonHelper $commonHelper)
    {
        $this->userRepository = $userRepository;
        $this->userHelper = $userHelper;
        $this->authHelper = $authHelper;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->transactionBalanceHelper = $transactionBalanceHelper;
        $this->advertisementHelper = $advertisementHelper;
        $this->commonHelper = $commonHelper;
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function storeUser(StoreUserRequest $request): JsonResponse
    {
        if (!empty((int)$request->phone) && !empty(User::withTrashed()
                ->where('phone', (int)$request->phone)
                ->first()))
            return response()->json(["phone" => [__('validation.unique', ['attribute' => 'phone'])]], 400);

        $role = Role::where("name", "user")->first();

        if (empty($role))
            return response()->json(["non_field_error" => [__("user.Role not found")]], 400);


        try {
            $user = User::create([
                "role_id" => $role->id,
                "name" => "Нет Имени",
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "phone" => empty($request->phone) ? null : (int)$request->phone,

            ]);

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [__("user.User is not registered") . $throwable->getMessage()]], 400);
        }

        try {
            if ($request->has("ref_code"))
                $this->userHelper->addReferralToUser($user->id, $request->get("ref_code"));

            $this->userHelper->addBonusRegistration($user);


        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
        }

        try {
            return response()->json(["data" =>
                $this->authHelper->fromUser($user)
            ]);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }
    }

    /**
     * @authenticated
     * @apiResourceModel App\Models\User
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {

        /**
         * @var User
         */
        $user = auth()->user();
        try {
            if ($user->longitude && $user->latitude) {
                $data = $this->GeoDBHelper->generateGeoDbDataByCoordinates($user->longitude, $user->latitude);
                foreach ($data as $key => $value)
                    $user->$key = $value;
            }
            return (new UserResource($user))->response();
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [__("user.User not found") . $throwable->getMessage()]], 400);
        }

    }

    /**
     * @authenticated
     * @param UploadImageRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadProfileAvatar(UploadImageRequest $request): JsonResponse
    {
        try {
            /**
             * @var UploadedFile
             */
            $file = $request->file("image");
            $user = auth()->user();
            /**
             * @var $user User
             */
            $this->userHelper->changeAvatar($user, $file);
            return response()->json(["data" => [
                "photo_url" => asset(Storage::url($user->avatar))
            ]]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["avatar" => [__("messages.Failed to upload main photo")]], 400);
        }
    }

    /**
     * @authenticated
     * @param UploadImagesRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadProfileAdditionalPhotos(UploadImagesRequest $request): JsonResponse
    {
        try {
            $files = $request->file("images");
            /**
             * @var $user User
             */
            $user = auth()->user();
            $this->userHelper->uploadProfileAdditionalPhotos($user, $files);
            return ImageResource::collection($user->images)->response();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(["additional_photos" => [__("messages.Failed to upload additional photo")]], 400);
        }
    }

    /**
     * @authenticated
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteProfileAvatar(): JsonResponse
    {
        try {
            /**
             * @var $user User
             */
            $user = auth()->user();
            $this->userHelper->deleteAvatar($user);
            return response()->json(["data" => ["message" => __("messages.Delete was successful")]]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(["additional_photos" => [__("messages.Failed to delete main photo")]], 400);
        }
    }

    /**
     * @authenticated
     * @param IdRequest $request
     * @return JsonResponse
     */
    public function deleteProfileAdditionalPhoto(IdRequest $request): JsonResponse
    {

        try {
            /**
             * @var $user User
             */
            $user = auth()->user();
            $this->userHelper->deleteProfileAdditionalPhoto($user, (int)$request->get("id"));
            return response()->json(["data" => ["message" => __("messages.Delete was successful")]]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(["non_field_error" => [__("messages.Failed to delete Additional photo")]], 400);
        }
    }

    /**
     * @authenticated
     * @param EditProfileRequest $editProfileRequest
     * @return JsonResponse
     */
    public function editProfile(EditProfileRequest $editProfileRequest): JsonResponse
    {
        try {
            /**
             * @var $user User
             */
            $user = auth()->user();

            if (!empty((int)$editProfileRequest->phone) &&
                !empty(User::withTrashed()
                    ->where('phone', (int)$editProfileRequest->phone)
                    ->where('id', '!=', $user->id)
                    ->first()))
                return response()->json(["phone" => [__('validation.unique', ['attribute' => 'phone'])]], 400);

            $validData = $editProfileRequest->validated();
            $validData['phone'] = empty((int)$editProfileRequest->phone) ? null : (int)$editProfileRequest->phone;

            if ($editProfileRequest->has("longitude") && $editProfileRequest->has("latitude")) {
                try {
                    $data = $this->GeoDBHelper->getLocationNearbyCities($editProfileRequest->get("latitude"), $editProfileRequest->get("longitude"), 10)->first();
                    if ($data)
                        $validData = array_merge([
                            'country' => $data->country,
                            'region' => $data->region,
                            'city' => $data->city,
                            'country_id' => $data->countryCode,
                            'region_id' => $data->regionCode,
                            'city_id' => $data->id,
                        ], $validData);
                } catch (Exception $exception) {
                    Log::critical("GEODB Error", $exception->getTrace());
                }
            }
            $user->update($validData);
            $user->save();

            UserSocial::where('user_id', $user->id)->delete();
            if (!empty($validData['social_media'])) {
                foreach ($validData['social_media'] as $social) {
                    if (empty($social["id"]) || empty($social['value']))
                        continue;

                    UserSocial::create(['user_id' => $user->id, 'social_id' => $social["id"], 'values' => $social['value']]);
                }

            }

            UserContact::where('user_id', $user->id)->delete();
            if (!empty($validData['contacts'])) {
                foreach ($validData['contacts'] as $contact) {
                    if (empty($contact["id"]) || empty($contact['value']))
                        continue;

                    UserContact::create(['user_id' => $user->id, 'contact_id' => $contact["id"], 'values' => $contact['value']]);
                }

            }

            UserPhone::where('user_id', $user->id)->delete();
            if (!empty($validData['additional_phones'])) {

                foreach ($validData['additional_phones'] as $phone) {
                    if (empty($phone))
                        continue;

                    UserPhone::create(['user_id' => $user->id, 'phone' => $phone]);
                }

            }


            return (new UserResource($user))->response();
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [__("user.Failed to save profile") . $throwable->getMessage()]], 400);
        }
    }

    /**
     * @authenticated
     * @param ChangePasswordRequest $changePasswordRequest
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $changePasswordRequest)
    {
        try {
            $validated = $changePasswordRequest->validated();
            /**
             * @var $user User
             */
            $user = auth()->user();
            $user->update(["password" => bcrypt($validated["password"])]);
            return response()->json(["data" => [
                "token" => JWTAuth::refresh(),
                "token_time_expired" => time() + 60 * config("jwt.ttl"),
            ]]);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(["non_field_error" => [__("user.Failed to change password")]], 400);
        }
    }

    /**
     * @authenticated
     * @param DeleteAccountRequest $deleteAccountRequest
     * @return JsonResponse
     */
    public function deleteAccount(DeleteAccountRequest $deleteAccountRequest)
    {
        try {
            $userId = auth()->id();
            EmailVerificationCode::where('user_id', $userId)->delete();
            User::where('id', $userId)->forceDelete();
            /*
            $user = User::find(auth()->id());

            if (!empty($user)) {
                $user->delete();
            }
            else
                return response()->json(["data" => [
                "message" => "User not found",
            ]], 404);
            */
            auth()->logout();
            JWTAuth::refresh();
            return response()->json(["data" => [
                "message" => "OK",
            ]]);
        } catch (Exception $exception) {
            return response()->json(["non_field_error" => [__("user.Failed to delete profile" . $exception->getMessage())]], 400);

        }
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function sendEmailVerificationNotification()
    {
        $user = User::find(auth()->id());

        $min = pow(10, $this->codeLength - 1);
        $max = pow(10, $this->codeLength) - 1;
        $code = random_int($min, $max);

        EmailVerificationCode::updateOrCreate(['user_id' => $user->id], ['code' => $code]);

        VerifyEmail::toMailUsing(function ($notifiable, $url) use ($code, $user) {
            return (new MailMessage)
                ->subject(__('email.Verify Email Address'))
                ->view('emails.verification', ['code' => $code, 'url' => $url, 'email' => $user->email]);
        });

        try {
            $user->sendEmailVerificationNotification();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(["non_field_error" => [$e->getMessage()]], 400);
        }

        return response()->json(["data" => ["message" => "mail_send"]]);
    }

    /**
     * @param CheckEmailVerificationCodeRequest $request
     * @return JsonResponse
     */
    public function checkEmailVerificationCode(CheckEmailVerificationCodeRequest $request): JsonResponse
    {
        $user = auth()->user();
        $emailVerificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->first();

        if (empty($emailVerificationCode))
            return response()->json(['non_field_error' => [__('user.Code is invalid')]], 400);

        $emailVerificationCode->delete();

        $objDateTime = new DateTime('NOW');
        $user->email_verified_at = $objDateTime->format('Y-m-d H:i:s');
        $user->save();

        return response()->json(['data' => ['message' => 'email_was_verifications']]);

    }

    /**
     * @return JsonResponse
     */
    public function getRef(): JsonResponse
    {
        $user = auth()->user();

        return (new RefResource($user))->response();
    }

    /**
     * @param TransferBonusToBalanceRequest $request
     * @return JsonResponse
     */
    public function transferBonusToBalance(TransferBonusToBalanceRequest $request): JsonResponse
    {
        $user = auth()->user();
        if ($user->bonus_balance < $request->get('bonus')) {
            return response()->json(['non_field_error' => [__('user.You dont have so much bonus_balance')]], 400);
        }
        /**
         * TODO ФОРМУЛЫ КОНВЕРТАЦИИ БОНУСОВ, ПОКА ЧТО 1 к 1
         */

        try {
            $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $request->get('bonus'),
                TransactionBalanceTypeEnum::WITHDRAWAL_FROM_BONUS_BALANCE,
                'Вывод с бонусного баланса',
                true);

            $this->transactionBalanceHelper->changeUserBalance($user,
                $request->get('bonus'),
                TransactionBalanceTypeEnum::REPLENISHMENT_FROM_BONUS_BALANCE,
                'Пополнение с бонусного баланса');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        return response()->json([
            "data" => [
                "bonus_balance" => $user->bonus_balance,
                "balance" => $user->balance,
            ]
        ]);
    }

    /**
     * @authenticated
     *
     * @param GetUserForGuestRequest $request
     * @return JsonResponse
     *
     * @queryParam user_id integer required The user id. Example: 4
     * @responseFile status=200 storage/responses/user/getUserForGuest.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getUserForGuest(GetUserForGuestRequest $request): JsonResponse
    {
        $user = User::where('id', $request->user_id)->first();

        if (empty($user))
            return response()->json(['non_field_error' => [__('user.User not found')]], 400);

        return response()->json(['data' => $this->userHelper->getUserForGuest($user)]);

    }

    /**
     * @authenticated
     *
     * @return JsonResponse
     */
    public function getAdvertisementLimit(): JsonResponse
    {
        $user = auth()->user();
        return response()->json(['data' => $this->userHelper->getAdvertisementLimit($user)]);
    }

    /**
     * @param UserForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function userForgotPassword(UserForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if (empty($user))
            return response()->json(['non_field_error' => [__('user.User not found')]], 400);

        try {
            $status = Password::sendResetLink(['email' => $user->email]);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => ['Mail has not been sent' . $throwable->getMessage()]], 400);
        }

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'We have emailed your password reset link!'])
            : response()->json(['non_field_error' => ['Mail has not been sent. Please wait before retrying']], 400);

    }

    /**
     * @param UserRestorePasswordRequest $request
     * @return JsonResponse
     */
    public function userRestorePassword(UserRestorePasswordRequest $request): JsonResponse
    {

        $email = '';
        $records = DB::table('password_resets')->get();
        foreach ($records as $record) {
            if (Hash::check(request('token'), $record->token)) {
                $email = $record->email;
                break;
            }
        }

        $request->merge(['email' => $email]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                if (empty($user->email_verified_at))
                    $user->email_verified_at = new DateTime('NOW');

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)])
            : response()->json(['non_field_error' => [__($status)]], 400);
    }

    /**
     * @authenticated
     *
     * @param UpdatePhoneRequest $request
     * @return JsonResponse
     */
    public function updatePhone(UpdatePhoneRequest $request): JsonResponse
    {
        try {
            /**
             * @var $user User
             */
            $user = auth()->user();

            if (!empty(User::withTrashed()
                ->where('phone', (int)$request->phone)
                ->where('id', '!=', $user->id)
                ->first()))
                return response()->json(["phone" => [__('validation.unique', ['attribute' => 'phone'])]], 400);

            $user->phone = (int)$request->phone;
            $user->phone_verified_at = null;
            $user->save();

            return response()->json(['data' => ['message' => 'user_phone_was_updated']]);

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [__("user.Failed to save profile") . $throwable->getMessage()]], 400);
        }

    }

    /**
     * @authenticated
     *
     * @return JsonResponse
     */
    public function getUserSettings(): JsonResponse
    {
        return response()->json(['data' => UserSetting::where('user_id', auth()->id())
            ->get(
                [
                    'is_hide_user',
                    'is_hide_company',
                    'is_receive_news',
                    'is_receive_messages_by_email',
                    'is_receive_comments_by_email',
                    'is_receive_price_favorite_by_email',
                    'is_receive_messages_by_phone',
                    'is_receive_comments_by_phone',
                    'is_receive_price_favorite_by_phone',
                ]
            )
        ]);
    }

    public function getUserSocialConnections()
    {
        $user = auth()->user();
        return response()->json(['data' => $this->userHelper->getUserSocialConnections($user)]);
    }

    /**
     * @param $driver
     * @return JsonResponse
     */
    public function handleSocialConnectionCallback($driver)
    {

        $this->authHelper->checkAuthDriverExists($driver);

        try {
            /**
             * @var \Laravel\Socialite\Two\User $socialUser
             */
            $socialUser = Socialite::driver($driver)->stateless()->user();

            $user = auth()->user();
            $this->userHelper->processSocialUser($socialUser, $driver, null, $user);

        } catch (Exception $exception) {
            return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
        }

        return response()->json(['data' => $this->userHelper->getUserSocialConnections($user)]);
    }

    /**
     * @authenticated
     *
     * @param SetHideUser $request
     * @return JsonResponse
     */
    public function setHideUser(SetHideUser $request): JsonResponse
    {
        $userId = auth()->id();
        $this->userHelper->updateUserSetting($userId, 'is_hide_user', $request->is_hide_user);
        if ($request->is_hide_user == true) {
            $date = $this->commonHelper->getInvertMonth();
            Advertisement::where('user_id', $userId)
                ->where('is_published', true)
                ->where('published_at', '>=', $date)
                ->where('is_hide', false)
                ->whereNull('company_id')
                ->get()
                ->each(function ($advertisement)  {

                    $advertisement->is_hide = true;
                    $this->advertisementHelper->addPromotion($advertisement, false);

                    $advertisement->save();

                });
        }

        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetHideCompany $request
     * @return JsonResponse
     */
    public function setHideCompany(SetHideCompany $request): JsonResponse
    {
        $user = auth()->user();
        $company = $user->company;

        if (empty($company))
            return response()->json(["non_field_error" => [__("company.Company not found")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_hide_company', $request->is_hide_company);

        $dateNow = new DateTime('NOW');
        $dateNowString = (new DateTime('NOW'))->format('Y-m-d');
        $dateNowString = $dateNowString . ' 23:59:59';

        if ($request->is_hide_company == true) {
            $date = $this->commonHelper->getInvertMonth();
            Advertisement::where('user_id', $user->id)
                ->where('is_published', true)
                ->where('published_at', '>=', $date)
                ->where('is_hide', false)
                ->where('company_id', $company->id)
                ->get()
                ->each(function ($advertisement)  {

                    $advertisement->is_hide = true;
                    $this->advertisementHelper->addPromotion($advertisement, false);

                    $advertisement->save();

                });

            $isAllocateAt = new DateTime($company->is_allocate_at);
            if ($isAllocateAt > $dateNow) {
                $interval = $dateNow->diff($isAllocateAt);
                $company->allocate_residue_days = (int) $interval->days;
                $company->is_allocate_at = $dateNowString;
            }

            $isTopAt = new DateTime($company->is_top_at);
            if ($isTopAt > $dateNow) {
                $interval = $dateNow->diff($isTopAt);
                $company->top_residue_days = (int) $interval->days;
                $company->is_top_at = $dateNowString;
            }

        } else {

            if ($company->allocate_residue_days > 0) {
                $company->is_allocate_at = $this->transactionBalanceHelper
                    ->addDays($company->allocate_residue_days, $company->is_allocate_at);

                $company->allocate_residue_days = 0;
            }

            if ($company->top_residue_days > 0) {
                $company->is_top_at = $this->transactionBalanceHelper
                    ->addDays($company->top_residue_days, $company->is_top_at);

                $company->top_residue_days = 0;
            }
        }

        $company->save();
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceiveNews $request
     * @return JsonResponse
     */
    public function setReceiveNews(SetReceiveNews $request): JsonResponse
    {
        $this->userHelper->updateUserSetting(auth()->id(), 'is_receive_news', $request->is_receive_news);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceiveMessagesByEmail $request
     * @return JsonResponse
     */
    public function setReceiveMessagesByEmail(SetReceiveMessagesByEmail $request): JsonResponse
    {
        $user = auth()->user();
        if (empty($user->email_verified_at))
            return response()->json(["non_field_error" => [__("auth.The user email address is not verified")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_receive_messages_by_email', $request->is_receive_messages_by_email);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceiveCommentsByEmail $request
     * @return JsonResponse
     */
    public function setReceiveCommentsByEmail(SetReceiveCommentsByEmail $request): JsonResponse
    {
        $user = auth()->user();
        if (empty($user->email_verified_at))
            return response()->json(["non_field_error" => [__("auth.The user email address is not verified")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_receive_comments_by_email', $request->is_receive_comments_by_email);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceivePriceFavoriteByEmail $request
     * @return JsonResponse
     */
    public function setReceivePriceFavoriteByEmail(SetReceivePriceFavoriteByEmail $request): JsonResponse
    {
        $user = auth()->user();
        if (empty($user->email_verified_at))
            return response()->json(["non_field_error" => [__("auth.The user email address is not verified")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_receive_price_favorite_by_email', $request->is_receive_price_favorite_by_email);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceiveMessagesByPhone $request
     * @return JsonResponse
     */
    public function setReceiveMessagesByPhone(SetReceiveMessagesByPhone $request): JsonResponse
    {
        $user = auth()->user();
        if (empty($user->phone_verified_at))
            return response()->json(["non_field_error" => [__("auth.The user phone is not verified")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_receive_messages_by_phone', $request->is_receive_messages_by_phone);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceiveCommentsByPhone $request
     * @return JsonResponse
     */
    public function setReceiveCommentsByPhone(SetReceiveCommentsByPhone $request): JsonResponse
    {
        $user = auth()->user();
        if (empty($user->phone_verified_at))
            return response()->json(["non_field_error" => [__("auth.The user phone is not verified")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_receive_comments_by_phone', $request->is_receive_comments_by_phone);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }

    /**
     * @authenticated
     *
     * @param SetReceivePriceFavoriteByPhone $request
     * @return JsonResponse
     */
    public function setReceivePriceFavoriteByPhone(SetReceivePriceFavoriteByPhone $request): JsonResponse
    {
        $user = auth()->user();
        if (empty($user->phone_verified_at))
            return response()->json(["non_field_error" => [__("auth.The user phone is not verified")]], 400);

        $this->userHelper->updateUserSetting($user->id, 'is_receive_price_favorite_by_phone', $request->is_receive_price_favorite_by_phone);
        return response()->json(['data' => ['message' => 'update_user_setting']]);
    }


}
