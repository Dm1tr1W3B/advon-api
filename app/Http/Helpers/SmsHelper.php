<?php

namespace App\Http\Helpers;


use App\Models\UserPhoneKey;
use App\Services\SmsService;
use \DateInterval;
use Exception;


class SmsHelper
{
    private $smsService;
    private $keyLength;
    private $keySendInterval;
    private $keyLifetime;
    private $keyLifetimeForRegistration;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
        $this->keyLength = config('auth.password_recovery.key_length');
        $this->keySendInterval = new DateInterval(config('auth.password_recovery.key_send_interval'));
        $this->keyLifetime = new DateInterval(config('auth.password_recovery.key_lifetime'));
        $this->keyLifetimeForRegistration = new DateInterval(config('auth.password_recovery.key_lifetime_for_registration'));
    }

    /**
     * @param $phone
     * @param $type
     * @throws Exception
     */
    public function getSmsKey($phone, $type)
    {
        $userPhoneKey = UserPhoneKey::where('user_phone', (int) $phone)
            ->where('type', $type)
            ->first();

        if (empty($userPhoneKey)) {
            $userPhoneKey = new UserPhoneKey;
            $userPhoneKey->user_phone = $phone;
            $userPhoneKey->type = $type;
        } else {
            $availableAt = $userPhoneKey->isRequestAvailable($this->keySendInterval);

            if ($availableAt !== true)
                throw new \Exception('sms timeout error');

        }

        $min = pow(10, $this->keyLength - 1);
        $max = pow(10, $this->keyLength) - 1;
        $key = random_int($min, $max);

        $text = 'Ваш код ' . $key;

        $userPhoneKey->key = $key;
        $userPhoneKey->save();

        $result = $this->smsService->send($phone, $text);

        if ($result === false)
            throw new \Exception('Send sms error');

        $userPhoneKey->life_time = $this->keyLifetime;
    }

    /**
     * @param $phone
     * @param $type
     * @param $key
     * @throws Exception
     */
    public function checkSmsKey($phone, $type, $key)
    {
        $userPhoneKey = UserPhoneKey::where('user_phone', $phone)
            ->where('type', $type)
            ->first();

        if (!$userPhoneKey || !$userPhoneKey->compareKey($key))
            throw new \Exception('Неправильный ключ.');

        $userPhoneKey->life_time = $this->keyLifetimeForRegistration;

        if (!$userPhoneKey->isActual())
            throw new \Exception('Срок действия ключа истек.');
    }

    /**
     * @param $phone
     * @param $type
     * @param $key
     * @return bool
     */
    public function deleteSMSKey($phone, $type, $key)
    {
        $userPhoneKey = UserPhoneKey
            ::where('user_phone', $phone)
            ->where('type', $type)
            ->first();

        if ($userPhoneKey && $userPhoneKey->compareKey($key)) {
            $userPhoneKey->delete();
            return true;
        }

        return false;
    }

    public function getBalance()
    {
        return $this->smsService->getBalance();
    }

}
