<?php

namespace App\Http\Helpers;


use App\Http\Enums\DepositStatusEnum;
use App\Models\Currency;
use App\Models\Deposit;
use Exception;
use Illuminate\Support\Facades\App;

class PaymentSystemHelper
{

    /**
     * @param $user
     * @return mixed
     */
    public function getUserCurrencyId($user)
    {
        $currencyId = $user->currency_id;
        if (empty($currencyId)) {
            $currency = Currency::where('code', 'RUB')->first();
            $currencyId = $currency->id;
        }

        return $currencyId;
    }

    /**
     * @param $user
     * @param $paymentSystem
     * @param $amount
     * @param $successUrl
     * @return mixed
     * @throws Exception
     */
    public function getRedirectUrl($user, $paymentSystem, $amount, $successUrl)
    {
        $method = $paymentSystem->prefix;

        if (!method_exists($this, $method))
            throw  new Exception (__('payment_system.Method not found'));

        return $this->$method($user, $paymentSystem, $amount, $successUrl);

    }

    /**
     * @param $user
     * @param $paymentSystem
     * @param $amount
     * @param $successUrl
     * @return string|string[]|null
     */
    private function robokassa($user, $paymentSystem, $amount, $successUrl)
    {
        $currencyId = $this->getUserCurrencyId($user);
        $description = 'Пополнение счета через Робокасса';

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'payment_system_id' => $paymentSystem->id,
            'amount' => $amount,
            'currency_id' => $currencyId,
            'status' => DepositStatusEnum::NEW,
            'description' => $description,
            'success_url' => $successUrl
        ]);

        $login = env('ROBOKASSA_LOGIN', 'advon.me'); // идентификатор магазина из раздела Технические настройки
        $pass1 = env('ROBOKASSA_PASS1', '');
        $signature = md5($login . ':' . $amount . ':' . $deposit->id . ':' . $pass1);

        $data = [
            'MerchantLogin' => $login,
            'OutSum' => $amount,
            'InvId' => $deposit->id,
            'Desc' => $description,
            'SignatureValue' => $signature,
            'IsTest' => (int)env('ROBOKASSA_IS_TEST', 1),
            'Culture' => App::getLocale(),
            'Email' => $user->email,
        ];

        $url = env('ROBOKASSA_BASE_URI', 'https://auth.robokassa.ru/Merchant/Index.aspx') . '?' . http_build_query($data, null, '&');

        return preg_replace('/[\r\n]+/is', '', $url);
    }

    /**
     * @param $user
     * @param $paymentSystem
     * @param $amount
     * @param $successUrl
     * @return string|string[]|null
     */
    private function payok($user, $paymentSystem, $amount, $successUrl)
    {
        $currencyId = $this->getUserCurrencyId($user);
        $description = 'Пополнение счета через Payok';

        $deposit = Deposit::create([
            'user_id' => $user->id,
            'payment_system_id' => $paymentSystem->id,
            'amount' => $amount,
            'currency_id' => $currencyId,
            'status' => DepositStatusEnum::NEW,
            'description' => $description,
            'success_url' => $successUrl
        ]);

        // Занесение параметров в массив
        $array = [
            $amount, // Сумма заказа.
            $deposit->id, // Номер заказа, уникальный в вашей системе, до 16 символов. (a-z0-9-_)
            env('PAYOK_SHOP_ID', 100), // ID вашего магазина.
            'RUB',
            $description, // Название или описание товара.
            env('PAYOK_SECRET_KEY', ''),
        ];

        // Соединение массива в строку и хеширование функцией md5
        $signature = md5 ( implode ( '|', $array ) );

        $data = [
            'amount' => $amount, // Сумма заказа.
            'payment' => $deposit->id, // Номер заказа, уникальный в вашей системе, до 16 символов. (a-z0-9-_)
            'shop' => env('PAYOK_SHOP_ID', 0), // ID вашего магазина.
            'desc' => $description, // Название или описание товара.
            'currency' => 'RUB',
            'sign' => $signature,
            'success_url' => $successUrl,
        ];

        $url = env('PAYOK_BASE_URI', 'https://payok.io/pay') . '?' . http_build_query($data, null, '&');

        return preg_replace('/[\r\n]+/is', '', $url);
    }

}
