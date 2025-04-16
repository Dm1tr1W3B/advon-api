<?php

namespace App\Http\Helpers;


use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Models\Advertisement;
use App\Models\Currency;
use App\Models\TransactionBalance;
use App\Models\UserSetting;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class TransactionBalanceHelper
{

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    public function __construct(LanguageHelper $languageHelper, CommonHelper $commonHelper)
    {
        $this->languageHelper = $languageHelper;
        $this->commonHelper = $commonHelper;
    }

    public function changeUserBalance($user,
                                      $amount,
                                      $type,
                                      $description = '',
                                      $isBonus = false,
                                      $depositId = null,
                                      $currencyId = null)
    {
        if (empty($currencyId)) {
            $currency = Currency::where('code', 'RUB')->first();
            $currencyId = $currency->id;
        }

        $transactionBalanceId = 0;

        DB::transaction(function () use ($user,
            $amount,
            $type,
            $description,
            $isBonus,
            $depositId,
            $currencyId,
            &$transactionBalanceId) {


            if ($isBonus) {
                $balance = (float) $user->bonus_balance;
                $newBalance = $user->bonus_balance + $amount;
                $user->bonus_balance  = $newBalance;
            } else {
                $balance = (float) $user->balance;
                $newBalance = $user->balance + $amount;
                $user->balance  = $newBalance;
            }


            $tTransactionBalance = TransactionBalance::create([
                'user_id' => $user->id,
                'deposit_id' => $depositId,
                'type' => $type,
                'currency_id' => $currencyId,
                'amount' => $amount,
                'balance' => $balance,
                'new_balance' => $newBalance,
                'description' => $description,
            ]);

            $user->save();

            $transactionBalanceId = $tTransactionBalance->id;
        });

        return $transactionBalanceId;
    }


    /**
     * @param int $days
     * @param $date
     * @return string
     * @throws \Exception
     */
    public function addDays(int $days, $date)
    {
        $dateNow = new DateTime('NOW');
        $dateAt = new DateTime($date);

        if ($dateAt > $dateNow) {
            $result = $dateAt->add(new DateInterval('P'. $days .'D'))->format('Y-m-d');
            return $result . ' 23:59:59';
        }

        $result = $dateNow->add(new DateInterval('P'. $days .'D'))->format('Y-m-d');
        return $result . ' 23:59:59';
    }

    /**
     * @param $user
     * @param int $advertisementId
     * @return mixed
     * @throws \Exception
     */
    public function getAdvertisement($user, int $advertisementId)
    {
        $advertisement = Advertisement::where('id', $advertisementId)
            ->where('user_id', $user->id)
            ->first();

        if (empty($advertisement))
            throw new \Exception(__('advertisement.Advertisement not found'));

        if ($advertisement->is_hide)
            throw new \Exception(__('advertisement.Advertisement is hide'));

        if (!$advertisement->is_published || empty($advertisement->published_at))
            throw new \Exception(__('advertisement.Advertisement not published (not active)'));

        $date = $this->commonHelper->getInvertMonth();
        if (new DateTime($advertisement->published_at) < $date)
            throw new \Exception(__('advertisement.Advertisement not published (not active)'));

        return $advertisement;

    }

    /**
     * @param $user
     * @return mixed
     * @throws \Exception
     */
    public function getCompany($user)
    {
        $company = $user->company;

        if (empty($company))
            throw new \Exception(__('company.Company not found'));

        $advertisement = Advertisement::where('company_id', $company->id)->first();
        if (empty($advertisement))
            throw new \Exception(__('company.Please add an advertisement to purchase company promotion'));

        $userSetting = UserSetting::where('user_id', $user->user_id)->first();
        if (!empty($userSetting->is_hide_company))
            throw new \Exception(__('company.Please open a company profile to purchase company promotion'));

        return $company;

    }

    /**
     * @param $user
     * @param string $sortType
     * @param string $sortColumnName
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function getTransactionBalances($user, string $sortType, string $sortColumnName)
    {
        $transactionBalances = TransactionBalance::where('user_id', $user->id)
            ->orderBy($sortColumnName, $sortType)
            ->get();

        if ($transactionBalances->isEmpty())
            return $transactionBalances;


        $result = collect([]);
        $transactionBalanceTypes = $this->languageHelper->getTranslations(array_values(TransactionBalanceTypeEnum::KEYS), App::getLocale());

        $transactionBalances->each(function ($transactionBalance) use (&$result, $transactionBalanceTypes){
            $item['id'] =  $transactionBalance->id;

            $createdAt = new DateTime($transactionBalance->created_at);
            $item['created_at'] =  $createdAt->format('d.m.y');

            $item['type'] = !empty(TransactionBalanceTypeEnum::KEYS[$transactionBalance->type])
                ? $transactionBalanceTypes[TransactionBalanceTypeEnum::KEYS[$transactionBalance->type]] : '';

            $item['amount'] = $transactionBalance->amount;

            $result->push($item);
        });

        if ($sortColumnName == 'type') {
            if ($sortType == 'ASC')
                return $result->sortBy('type');

            return $result->sortByDesc('type');
        }

        return $result;
    }

}
