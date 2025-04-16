<?php

namespace App\Http\Helpers;

use App\Mail\ActiveAdvertisementEmail;
use App\Mail\PriceAdvertisementEmail;
use App\Models\Advertisement;
use App\Models\AdvertisementFavorite;
use App\Models\Company;
use App\Models\Image;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\AdvertisementRepository;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommonHelper
{

    private $numberOfHashtags = 32;

    /**
     * @var AdvertisementRepository
     */
    private $advertisementRepository;

    public function __construct(AdvertisementRepository $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
    }

    /**
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    /**
     * @param $advertisement
     * @param false $isPublished
     * @param false $isPrice
     */
    public function sendMailRecipientUser($advertisement, $isPublished = false, $isPrice = false)
    {
        if (!$isPublished && !$isPrice)
            return;

        $subscriptions = Subscription::join('users as recipient_users', 'recipient_users.id', '=', 'subscriptions.recipient_user_id')
            ->select('recipient_users.email');

        if (!empty($advertisement->company_id)) {
            $subscriptions = $subscriptions->where('subscriptions.sender_company_id', $advertisement->company_id);
            $author = Company::find($advertisement->company_id);
        } else {
            $subscriptions = $subscriptions->where('subscriptions.sender_user_id', $advertisement->user_id);
            $author = User::find($advertisement->user_id);
        }

        if ($isPrice) {
            // todo  подписка на оюъяву
            //$subscriptionsPrice = $subscriptions->where('subscriptions.advertisement_id', $advertisement->id)
            $subscriptionsPrice = $subscriptions
                ->get();

            $emails = [];
            if ($subscriptionsPrice->isNotEmpty())
                $emails = $subscriptionsPrice->pluck('email')->all();

            $favorites = AdvertisementFavorite::join('users', 'users.id', '=', 'advertisement_favorites.user_id')
                ->join('user_settings', 'user_settings.user_id', '=', 'users.id')
                ->select('users.email')
                ->where('advertisement_favorites.advertisement_id', $advertisement->id);

            $favoriteEmails = $favorites->where('user_settings.is_receive_price_favorite_by_email',true)
                ->get();

            if ($favoriteEmails->isNotEmpty())
                $emails = $emails + $favoriteEmails->pluck('email')->all();

            try {
                if (!empty($emails))
                    foreach ($emails as $email)
                        Mail::to($email)->send(new PriceAdvertisementEmail($advertisement, $email));
            } catch (\Throwable $throwable) {
                Log::error($throwable->getMessage());
            }

            // todo is_receive_price_favorite_by_phone
        }

        if ($isPublished) {
            $subscriptions = $subscriptions->get();

            if ($subscriptions->isEmpty())
                return;

            $emails = $subscriptions->pluck('email');

            $image = $advertisement->photo_id ?
                asset(Storage::url(Image::find($advertisement->photo_id)->photo_url)) :
                url('storage/default/product.png');

            try {
                if (!empty($emails))
                    foreach ($emails as $email)
                        Mail::to($email)->send(new ActiveAdvertisementEmail($advertisement, $email, $author, $image));
            } catch (\Throwable $throwable) {
                Log::error($throwable->getMessage());
            }
        }
    }

    /**
     * @param string $folder
     * @param UploadedFile $file
     * @return false|string
     */
    public function uploadedFile(string $folder, UploadedFile $file)
    {
        $name = $file->getClientOriginalName();
        $date = new Carbon();
        $date->locale('en');
        $folder = "$folder/" . $date->monthName . $date->year;

        if (!File::exists(public_path('storage/' . $folder))) {
            File::makeDirectory(public_path('storage/' . $folder), 0755, true);
        }

        $path = $file->store('public/' . $folder);

        return json_encode([[
            'download_link' => Str::after($path, 'public'),
            'original_name' => $name,
        ]]);

    }


    /**
     * @param $model
     * @param int $id
     * @throws Exception
     */
    public function deleteAdditionalPhoto($model, int $id)
    {

        try {
            if ($model->photo_id == $id) {
                $model->photo_id = null;
                $model->save();
            }

            $images = $model->images;
            if ($images->find($id)) {
                $model->images()->detach($id);
                Image::destroy($id);
            } else
                throw new Exception(__("messages.This image not belongs this model"));
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }



    /**
     * @return DateTime
     */
    public function getInvertMonth()
    {
        $dateInterval = new DateInterval('P1Y');
        $dateInterval->invert = 1;
        $dateTime = new DateTime('NOW');
        return $dateTime->add($dateInterval);
    }

    /**
     * @return array
     */
    public function getPopularHashtags()
    {
        $hashtags = [];

        // todo убрать комментарии когда будет понятно как что делать с хештегами компании
        /*
        Company::get()->each(function ($company) use (&$hashtags) {

            if (empty($company->hashtags))
                return true;

            $companyHashtags = explode(',', $company->hashtags);

            if (!is_array($companyHashtags))
                $companyHashtags = [$company->hashtags];

            $this->addHashtags($hashtags, $companyHashtags);
        });
        */

        $date = $this->getInvertMonth();

        $advertisements = Advertisement::join('users as u', 'u.id', '=', 'advertisements.user_id')
            ->where('advertisements.is_published', true)
            ->where('advertisements.published_at', '>=', $date);

        $advertisements = $this->advertisementRepository->addUserSettings($advertisements);

        $advertisements->get()
            ->each(function ($advertisement) use (&$hashtags) {

            if (empty($advertisement->hashtags) || !is_array($advertisement->hashtags))
                return true;

            $this->addHashtags($hashtags, $advertisement->hashtags);
        });

        natsort($hashtags);
        $sliceHashtags = array_slice($hashtags, -1 * $this->numberOfHashtags, $this->numberOfHashtags, true);

        return array_keys($sliceHashtags);
    }

    /**
     * @param array $hashtags
     * @param array $newHashtags
     */
    private function addHashtags(array &$hashtags, array $newHashtags)
    {
        foreach ($newHashtags as $hashtag) {
            $hashtag = trim($hashtag);

            if (empty($hashtag))
                continue;

            if (Str::length($hashtag) > 14)
                continue;

            if (!empty($hashtags[$hashtag])) {
                $hashtags[$hashtag] = $hashtags[$hashtag] + 1;
                continue;
            }

            $hashtags[$hashtag] = 1;
        }
    }
}
