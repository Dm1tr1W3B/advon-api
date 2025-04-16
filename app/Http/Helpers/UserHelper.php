<?php

namespace App\Http\Helpers;


use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Http\Resources\ImageResource;
use App\Models\Advertisement;
use App\Models\Bonus;
use App\Models\Category;
use App\Models\Image;
use App\Models\SocialType;
use App\Models\SocialUser;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserReferral;
use App\Models\UserSetting;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use TCG\Voyager\Models\Role;

class UserHelper
{
    public static $maxCountOfAdditionalImages = 10;
    public $bonusForAnotherUser = 1;
    public $bonusForRegistration = 200;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var AuthHelper
     */
    private $authHelper;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    /**
     * @var TransactionBalanceHelper
     */
    private $transactionBalanceHelper;

    public function __construct(UserRepository $userRepository,
                                GeoDBHelper $GeoDBHelper,
                                LanguageHelper $languageHelper,
                                CommonHelper $commonHelper,
                                AuthHelper $authHelper,
                                TransactionBalanceHelper $transactionBalanceHelper)
    {
        $this->userRepository = $userRepository;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->languageHelper = $languageHelper;
        $this->commonHelper = $commonHelper;
        $this->authHelper = $authHelper;
        $this->transactionBalanceHelper = $transactionBalanceHelper;
    }

    /**
     * @param User $user
     * @param UploadedFile $newAvatar
     * @return void
     * @throws Exception
     */
    public function changeAvatar(&$user, UploadedFile $newAvatar)
    {
        try {
            $filename = Str::random(20);
            $extension = $newAvatar->getClientOriginalExtension();
            $date = new Carbon();
            $date->locale("en");
            $folder = "users/" . $date->monthName . $date->year;
            $newAvatar->move(public_path('storage/' . $folder), $filename . '.' . $extension);
            $this->deleteAvatar($user);
            $user->avatar = $folder . '/' . $filename . '.' . $extension;
            $user->save();
        } catch (\Exception $exception) {
            throw  new Exception("error to change avatar");
        }
    }

    /**
     * @param User $user
     * @param array $additionalPhotos
     * @throws Exception
     */
    public function uploadProfileAdditionalPhotos($user, array $additionalPhotos)
    {
        $photos_id = [];

        if (($user->images()->count() + count($additionalPhotos)) > self::$maxCountOfAdditionalImages)
            throw  new Exception("Exceeded the number of images.Maximum number is :" . self::$maxCountOfAdditionalImages);

        foreach ($additionalPhotos as $additionalPhoto) {
            $photos_id[] = ImageHelper::createPhotoFromRequest('user_additional_photos', $additionalPhoto)->id;
        }

        $user->images()->attach($photos_id);
    }

    /**
     * @param User $user
     * @throws Exception
     */
    public function deleteAvatar($user)
    {
        try {
            if (empty($user->avatar))
                return;

            if ($user->avatar == 'users/default.png')
                return;

            File::delete(public_path('storage/' . $user->avatar));
            $user->avatar = null;
            $user->save();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @throws Exception
     */
    public function deleteProfileAdditionalPhoto($user, int $id)
    {
        try {
            $images = $user->images();
            if ($images->find($id)) {
                $images->detach($id);
                Image::destroy($id);
            } else
                throw new Exception("This Image Not Belongs This user");
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }


    /**
     * @param $referral_id int
     * @param $refCode string
     */
    public function addReferralToUser($referral_id, $refCode)
    {
        $user = User::where("ref_code", $refCode)->first();
        if ($user) {
            UserReferral::create(["user_id" => $user->id, "referral_id" => $referral_id]);

            Bonus::where('bonus_type_id', 2)
                ->where('is_active', true)
                ->get()
                ->each(function ($bonus) use ($user, $referral_id) {
                    $this->transactionBalanceHelper->changeUserBalance($user,
                        $bonus->amount,
                        $bonus->is_real_balance ? TransactionBalanceTypeEnum::BONUS_REFERRAL_REAL : TransactionBalanceTypeEnum::BONUS_REFERRAL,
                        'Бонус #' . $bonus->id . ' за приглашенного человека user_id#' . $referral_id,
                        !$bonus->is_real_balance);
                });
        }
    }

    /**
     * @param $user
     */
    public function addBonusRegistration($user)
    {
        Bonus::where('bonus_type_id', 1)
            ->where('is_active', true)
            ->get()
            ->each(function ($bonus) use ($user) {
                $this->transactionBalanceHelper->changeUserBalance($user,
                    $bonus->amount,
                    $bonus->is_real_balance ? TransactionBalanceTypeEnum::BONUS_REGISTRATION_REAL : TransactionBalanceTypeEnum::BONUS_REGISTRATION,
                    'Бонус за регистрацию #' . $bonus->id,
                    !$bonus->is_real_balance);
            });
    }

    /**
     * @param $userId
     * @param $contactShow
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getUserContacts($userId, $contactShow)
    {
        return $this->userRepository->getUserContacts($userId)
            ->map(function ($contact) use ($contactShow) {
                $contact->photo_url = url('storage/' . $contact->photo_url);

                if (!$contactShow)
                    $contact->values = Str::limit($contact->values, 2, 'XXXXXXX');

                return $contact;
            });
    }

    public function getUserSocial($userId, $contactShow)
    {
        return $this->userRepository->getUserSocial($userId)
            ->map(function ($social) use ($contactShow) {
                $social->photo_url = url('storage/' . $social->photo_url);

                $smt = [
                    'Facebook' => 'https://www.facebook.com/',
                    'Instagram' => 'https://www.instagram.com/',
                    'VK' => 'https://vk.com/',
                    'OK' => 'https://ok.ru/',
                ];

                if (!$contactShow)
                    $social->values = ''; // Str::limit($social->values, 2, 'XXXXXXX');
                else {
                    $social->values = !empty($smt[$social->name]) ? $smt[$social->name] . $social->values : '';
                }

                return $social;


                return $social;
            });
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUserForGuest($user)
    {
        $userForGuest['main'] = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_description' => empty($user->description) ? '' : $user->description,
            'additional_photos' => ImageResource::collection($user->images),
            'city' => $this->getUserCity($user),
            'created_at' => $user->created_at->format('d.m.Y'),
        ];

        $contactShow = false;

        $phone = '';
        if (!empty($user->phone))
            $phone = $contactShow ? (int)$user->phone : Str::limit((int)$user->phone, 2, 'XXXXXXX');

        $userForGuest['person'] = [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => (!empty($user->avatar) && $user->avatar != 'users/default.png') ?
                asset(Storage::url($user->avatar)) : url('storage/default/user.png'),
            'phone' => $phone,
            'contacts' => $this->getUserContacts($user->id, $contactShow),
            'social' => $this->getUserSocial($user->id, $contactShow),
            'created_at' => $user->created_at->format('d.m.Y'),
            'number_advertisement' => Advertisement::where('user_id', $user->id)
                ->where('is_published', true)
                ->where('published_at', '>=', $this->commonHelper->getInvertMonth())
                ->where('is_hide', false)
                ->whereNull('company_id')
                ->count(),
        ];

        $userForGuest['isSubscription'] = false;

        $userAuth = $this->authHelper->getUser();
        if (!empty($userAuth)) {
            $subscription = Subscription::where('recipient_user_id', $userAuth->id)
                ->where('sender_user_id', $user->id)
                ->first();

            if (!empty($subscription))
                $userForGuest['isSubscription'] = true;
        }

        return $userForGuest;
    }

    /**
     * @param $user
     * @return string
     */
    public function getUserCity($user)
    {
        $city = '';

        if (!empty($user->city) && App::getLocale() == 'ru')
            return $user->city;

        if (!empty($user->latitude) && !empty($user->longitude)) {

            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($user->latitude,
                    $user->longitude,
                    10,
                    App::getLocale())->first();

                if (!empty($data->city))
                    return $data->city;
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
                return '';
            }
        }

        return $city;
    }

    /**
     * @param $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getAdvertisementLimit($user)
    {
        $date = $this->commonHelper->getInvertMonth();

        $advertisementActive = $this->userRepository->gettAdvertisementActive($user, $date);
        $categoryKeys = $advertisementActive->pluck('category_key')->all();
        $translationCategories = $this->languageHelper->getTranslations($categoryKeys, App::getLocale());
        $categories = Category::whereIn('key', $categoryKeys)->get();


        $userCategories = $this->userRepository->getUserCategories($user);

        return $advertisementActive->map(function ($item) use ($translationCategories, $userCategories, $categories) {
            $item->category_name = $translationCategories[$item->category_key];

            $item->limit = $categories->where('key', $item->category_key)->sum('limit');
            $userCategory = $userCategories->where('category_key', $item->category_key)->first();
            if (!empty($userCategory))
                $item->limit += $userCategory->limit;

            return $item;
        });
    }

    /**
     * @param int $userId
     * @param string $field
     * @param bool $value
     */
    public function updateUserSetting(int $userId, string $field, bool $value)
    {
        UserSetting::where('user_id', $userId)
            ->update([$field => $value]);
    }


    /**
     * @param $email
     * @param $social_id
     * @param $social_type
     * @return User|null
     */
    public function findUserWithSocial($email, $social_id, $social_type)
    {


        $socialUser = SocialUser::where(function ($query) use ($social_type, $social_id) {
            $query->where('social_id', $social_id)
                ->where('social_type', $social_type)
                ->whereNotNull('user_id');
        })->orWhere(function ($query) use ($email) {
            $query->where('email', $email)
                ->whereNotNull('user_id');
        })->first();

        if ($socialUser) {
            return User::find($socialUser->user_id);
        }
        if ($user = User::where('email', $email)->first()) {
            return $user;
        }
        return null;
    }

    /**
     * @param $email
     * @param $name
     * @param $nickname
     * @param $avatar
     * @param null $password
     * @param null $ref_code
     * @param null $email_verified
     * @return User
     */
    public function createUserFromSocial($email, $name, $nickname, $avatar, $password = null, $ref_code = null, $email_verified = null)
    {
        $now = Carbon::now()->toDateTime();
        $name = $name ? $name : $nickname;
        $avatarPath = null;
        if ($avatar) {
            $avatarPath = ImageHelper::createPhotoFromURL('users', $avatar)->photo_url;
        }

        $role = Role::where("name", "user")->first();


        $user = User::create([
            'email' => $email,
            'name' => $name ? $name : __('user.No name'),
            'email_verified_at' => $email_verified ? $now : null,
            'password' => $password ? Hash::make($password) : Hash::make($email . $name . $now->format('Y-m-d')),
            'role_id' => $role->id,
            'avatar' => $avatarPath
        ]);
        if ($ref_code) {
            $this->addReferralToUser($user->id, $ref_code);
        }
        $this->addBonusRegistration($user);

        return $user;
    }

    public function addSocialForUser($socialData, $user)
    {
        SocialUser::create(array_merge($socialData, ['user_id' => $user->id]));
    }

    /**
     *
     * @param \Laravel\Socialite\Two\User $socialUser
     * @param $driver
     * @param null $ref_code
     * @param null $user
     * @return User|null
     */
    public function processSocialUser($socialUser, $driver, $ref_code = null, $user = null)
    {
        /**
         * @var User $user
         */
        $socialData = [
            'social_type' => $driver,
            'social_id' => $socialUser->id,
            'name' => $socialUser->name,
            'nickname' => $socialUser->nickname,
            'email' => $socialUser->email,
            'avatar' => $socialUser->avatar,
        ];
        $token = $socialUser->token;
        if ($user) {
            $this->addSocialForUser($socialData, $user);
        } else {
            $user = $this->findUserWithSocial($socialData['email'], $socialData['social_id'], $socialData['social_type']);
        }
        if (!$user && $socialData['email'] && ($socialData['name'] || $socialData['nickname'])) {

            $user = $this->createUserFromSocial($socialData['email'],
                $socialData['name'],
                $socialData['nickname'],
                $socialData['avatar'],
                null,
                $ref_code,
                true
            );
        }

        if ($socialUser->id) {
            SocialUser::updateOrCreate($socialData,
                array_merge(
                    $socialData,
                    ['token' => $token, 'user_id' => $user ? $user->id : null]
                )
            );
        }

        return $user;


    }

    public function getUserSocialConnections($user)
    {
        $socialTypes = SocialType::where('active', true)->get();
        $socialUser = SocialUser::where('user_id', $user->id)->get();

        return $socialTypes->map(function ($type) use ($socialUser) {
            $type->user_exists = $socialUser->firstWhere('social_type', $type->key) ? true : false;

            $type->image=url($type->image);

            if (!$type->user_exists) {
                $type->login_url = Socialite::driver($type->key)->stateless()->redirect()->getTargetUrl();
            }

            unset($type->id, $type->active);

            return $type;
        });

    }
}
