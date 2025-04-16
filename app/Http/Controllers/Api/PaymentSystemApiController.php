<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LanguageHelper;
use App\Http\Helpers\PaymentSystemHelper;
use App\Http\Requests\GetRedirectUrlRequest;
use App\Http\Resources\ContactResource;
use App\Models\ContactType;
use App\Models\Currency;
use App\Models\PaymentSystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;


/**
 * Class PaymentSystemApiController
 * @package App\Http\Controllers\Api
 * @group PaymentSystem
 */
class PaymentSystemApiController extends Controller
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


    /**
     * @return JsonResponse
     */
    public function getActiveCurrencies(): JsonResponse
    {
        $currencies = Currency::where('enabled', true)->get(['id', 'code']);

        $currencyCodes = $currencies->pluck('code')->unique()->values()->all();
        $translationCurrencyCodes = $this->languageHelper->getTranslations($currencyCodes, App::getLocale());

        $currencies->map(function ($currency) use ($translationCurrencyCodes) {
            $currency->currency_id = $currency->id;
            $currency->translation_currency_code = $translationCurrencyCodes[$currency->code];

            unset($currency->id, $currency->code);
        });

        return response()->json(['data' => $currencies]);
    }


    /**
     * @authenticated
     *
     * @return JsonResponse
     */
    public function getPaymentSystems(): JsonResponse
    {
        $user = auth()->user();

        $currencyId = $this->paymentSystemHelper->getUserCurrencyId($user);

        $paymentSystems = PaymentSystem::leftjoin('payment_system_currencies as psc', 'psc.payment_system_id', '=', 'payment_systems.id')
            ->leftjoin('images as i', 'i.id', '=', 'payment_systems.image_id')
            ->select(
                'payment_systems.id as payment_system_id',
                'payment_systems.name as payment_system_name',
                'i.photo_url'
            )
            ->where('payment_systems.is_active', true)
            ->where('psc.currency_id', $currencyId)
            ->get()
            ->map(function ($paymentSystem) {
                $paymentSystem->image = !empty($paymentSystem->photo_url) ? url('storage/' . $paymentSystem->photo_url) : '';

                unset($paymentSystem->photo_url);

                return $paymentSystem;
            });

        return response()->json(['data' => $paymentSystems]);
    }

    /**
     * @authenticated
     *
     * @param GetRedirectUrlRequest $request
     * @return JsonResponse
     *
     * @queryParam payment_system_id integer required The payment system id.  Example: 1
     * @queryParam amount number required The payment amount.  Example: 13.00
     * @queryParam success_url string required Success return page. Example: "https://www.page/"
     * @responseFile status=200 storage/responses/paymentSystem/getRedirectUrl.json
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getRedirectUrl(GetRedirectUrlRequest $request): JsonResponse
    {
        $paymentSystem = PaymentSystem::where('id', $request->payment_system_id)
            ->where('is_active', true)
            ->first();

        if (empty($paymentSystem))
            return response()->json(['non_field_error' => [__('payment_system.Payment system not found')]], 400);

        try {
            $redirectUrl = $this->paymentSystemHelper->getRedirectUrl(auth()->user(),
                $paymentSystem, $request->amount,
                $request->success_url);
        } catch (\Throwable $throwable) {
            Log::error('PaymentSystem.getRedirectUrl ' . $throwable->getMessage());
            return response()->json(['non_field_error' => [$throwable->getMessage()]], 400);
        }


        return response()->json(['data' => ['redirect_url' => $redirectUrl]]);

    }

}
