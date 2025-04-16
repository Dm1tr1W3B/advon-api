<?php

namespace App\Http\Controllers\Callback;

use App\Http\Controllers\Controller;
use App\Http\Enums\DepositStatusEnum;
use App\Http\Helpers\LanguageHelper;
use App\Http\Helpers\PaymentSystemHelper;

use App\Models\Deposit;
use App\Models\PaymentSystem;
use App\Models\User;
use Illuminate\Support\Facades\Log;


/**
 * Class RobokassaController
 * @package App\Http\Controllers\Callback
 * @group Robokassa
 */
class RobokassaController extends Controller
{
    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    /**
     * @var PaymentSystemHelper
     */
    private $paymentSystemHelper;

    public function __construct(LanguageHelper $languageHelper, PaymentSystemHelper $paymentSystemHelper)
    {
        $this->languageHelper = $languageHelper;
        $this->paymentSystemHelper = $paymentSystemHelper;
    }

    // оработка результата
    public function notify()
    {

        $method = 'notify';
        list($paymentSystem, $deposit, $user) = $this->inputValidation($method);

        if ($deposit->status == DepositStatusEnum::PAID) {
            Log::error('RobokassaController.'.$method .' Повторное закрытие депозита #' . $deposit->id);
            $deposit->description = $deposit->description . ' '. 'RobokassaController.'.$method .' Повторное закрытие депозита';
            $deposit->save();

            echo "OK$deposit->id" . PHP_EOL;
        }

        if ($deposit->status == DepositStatusEnum::SUCCESS)
            $this->sendBadResponse('Депозит #' . $deposit->id . 'в статусе успех', $method, $deposit);

        if ($deposit->status == DepositStatusEnum::REJECTED)
            $this->sendBadResponse('Депозит #' . $deposit->id . 'в статусе откланен', $method, $deposit);


        $deposit->status = DepositStatusEnum::PAID;
        $deposit->save();

        // todo
        //  пополняем счет пользователя на сумму
        //  запись в таблицу транзакций
        //  отправим почтовое уведомление пользователю

        $user->balance = $user->balance + $deposit->amount;
        $user->save();

        echo "OK$deposit->id" . PHP_EOL;


    }

    // адрес страницы, на которую отправится покупатель после оплаты,
    public function success()
    {
        $method = 'success';
        list($paymentSystem, $deposit, $user) = $this->inputValidation($method);

        if ($deposit->status == DepositStatusEnum::REJECTED)
            $this->sendBadResponse('Депозит #' . $deposit->id . 'в статусе откланен', $method, $deposit);

        if ($deposit->status == DepositStatusEnum::SUCCESS) {
            Log::error('RobokassaController.'.$method .' Повторный успех #' . $deposit->id);
            $deposit->description = $deposit->description . ' '. 'RobokassaController.'.$method .' Повторный успех депозита';
            $deposit->save();

            // todo redirect to page success
            echo "Операция прошла успешно" . PHP_EOL;
        }

        $deposit->status = DepositStatusEnum::SUCCESS;
        $deposit->save();

        // todo redirect to page success
        echo "Операция прошла успешно" . PHP_EOL;

    }

    //  адрес страницы, на которую направляется покупатель после неудачной оплаты
    public function fail()
    {
        $method = 'fail';

        $depositId = request('InvId');

        if (empty($depositId)) {
            $message = 'Не передан параметр InvId';
            Log::error('RobokassaController.'.$method .' '. $message);
            // todo redirect to page fail
        }

        $deposit = Deposit::find($depositId);
        if (empty($deposit)) {
            $message = 'Депозит #' . $depositId . ' не найден';
            Log::error('RobokassaController.'.$method .' '. $message);
            // todo redirect to page fail
        }

        $deposit->status = DepositStatusEnum::REJECTED;
        $deposit->save();
        $message =  "Вы отказались от оплаты. Заказ# $depositId";
        // todo redirect to page fail
    }

    /**
     * @param string $message
     * @param string $method
     * @param null $deposit
     */
    private function sendBadResponse(string $message, string $method, $deposit = null)
    {
        Log::error('RobokassaController.'.$method .' '. $message);

        if ($deposit) {
            $deposit->description = $deposit->description . ' '. 'RobokassaController.'.$method .' '. $message;
            $deposit->save();
        }

        echo $message . PHP_EOL;
        exit();
    }

    /**
     * @param string $method
     */
    private function inputValidation(string $method)
    {
        $signature = request('SignatureValue');
        $depositId = request('InvId');
        $amount = request('OutSum');

        if (empty($signature))
            $this->sendBadResponse('Не передан параметр SignatureValue', $method);

        if (empty($depositId))
            $this->sendBadResponse('Не передан параметр InvId', $method);

        if (empty($amount))
            $this->sendBadResponse('Не передан параметр OutSum', $method);

        $pass2 = env('ROBOKASSA_PASS2', '');
        if (strtoupper($signature) != strtoupper(md5("$amount:$depositId:$pass2")))
            $this->sendBadResponse('Неверная контрольная сумма', $method);

        $paymentSystem = PaymentSystem::where('prefix', 'robokassa')
            ->where('is_active', true)
            ->first();
        if (empty($paymentSystem))
            $this->sendBadResponse('Платежная система не найдена', $method);

        $deposit = Deposit::find($depositId);
        if (empty($deposit))
            $this->sendBadResponse('Депозит #' . $depositId . ' не найден', $method);

        if ($deposit->payment_system_id != $paymentSystem->id)
            $this->sendBadResponse('Cчёт #' . $deposit->id . ' выставлен для оплаты другой системой оплаты',
                $method, $deposit);

        if ((float)$deposit->amount != (float)$amount)
            $this->sendBadResponse('Неверная сумма ' . $amount . 'депозита #' . $depositId, $method, $deposit);

        $user = User::find($deposit->user_id);
        if (empty($user))
            $this->sendBadResponse('Пользователь не найден', $method, $deposit);

    }



}
