<?php

namespace App\Http\Controllers\Callback;

use App\Http\Controllers\Controller;
use App\Http\Enums\DepositStatusEnum;
use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Http\Helpers\TransactionBalanceHelper;
use App\Models\Deposit;
use App\Models\PaymentSystem;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Class PayokController
 * @package App\Http\Controllers\Callback
 * @group Payok
 */
class PayokController extends Controller
{
    /**
     * @var TransactionBalanceHelper
     */
    private $transactionBalanceHelper;

    public function __construct(TransactionBalanceHelper $transactionBalanceHelper)
    {
        $this->transactionBalanceHelper = $transactionBalanceHelper;
    }


    public function callback()
    {
        $signature = request('sign');
        $depositId = request('payment_id');
        $amount = request('amount');

        if (empty($signature))
            return $this->sendBadResponse('Не передан параметр sign');

        if (empty($depositId))
            return $this->sendBadResponse('Не передан параметр payment_id');

        if (empty($amount))
            return $this->sendBadResponse('Не передан параметр amount');

        $deposit = Deposit::find($depositId);
        if (empty($deposit))
            return $this->sendBadResponse('Депозит #' . $depositId . ' не найден');

        $array = [
            env('PAYOK_SECRET_KEY', ''),
            request('desc'),
            request('currency'),
            request('shop'),
            $depositId,
            $amount,
        ];

        // Соединение массива в строку и хеширование функцией md5
        $sign = md5(implode('|', $array));
        if ($sign != $signature)
            return $this->sendBadResponse('Подпись не совпадает.', $deposit);

        if ($deposit->status == DepositStatusEnum::PAID) {
            Log::error('PayokController.callback Повторное закрытие депозита #' . $deposit->id . ' request ' . json_encode(request()->toArray()));
            $deposit->description = $deposit->description . ' PayokController.callback Повторное закрытие депозита #' . $deposit->id . ' request ' . json_encode(request()->toArray());
            $deposit->save();

            return response('OK');
        }

        $paymentSystem = PaymentSystem::where('prefix', 'payok')
            ->where('is_active', true)
            ->first();
        if (empty($paymentSystem))
            return $this->sendBadResponse('Платежная система не найдена', $deposit);

        if ($deposit->payment_system_id != $paymentSystem->id)
            return $this->sendBadResponse('Cчёт #' . $deposit->id . ' выставлен для оплаты другой системой оплаты', $deposit);

        if ((float)$deposit->amount != (float)$amount)
            return $this->sendBadResponse('Неверная сумма ' . $amount . 'депозита #' . $depositId, $deposit);

        $user = User::find($deposit->user_id);
        if (empty($user))
            return $this->sendBadResponse('Пользователь не найден', $deposit);

        $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance(
            $user,
            $amount,
            TransactionBalanceTypeEnum::DEPOSIT,
            'Пополнение через Payok',
            false,
            $depositId
        );
        if (empty($transactionBalanceId))
            return $this->sendBadResponse('Ошибка изменения баланса', $deposit);

        $deposit->status = DepositStatusEnum::PAID;
        $deposit->save();

        return response('OK');
    }

    /**
     * @param string $message
     * @param string $method
     * @param null $deposit
     */
    private function sendBadResponse(string $message, $deposit = null)
    {
        Log::error('PayokController.callback ' . $message . ' request ' . json_encode(request()->toArray()));

        if ($deposit) {
            $deposit->description = $deposit->description . ' ' . 'PayokController.callback ' . $message . ' request ' . json_encode(request()->toArray());
            $deposit->save();
        }

        return response($message, 500);
    }
}
