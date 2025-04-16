<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Http\Helpers\CommonHelper;
use App\Http\Helpers\TransactionBalanceHelper;
use App\Http\Requests\GetTransactionBalanceForUserRequest;
use App\Http\Requests\IncreaseLimitAdvertisementCategoryRequest;
use App\Http\Requests\IncreaseLimitViewContactUserRequest;
use App\Http\Requests\SetAdvertisementAllocateRequest;
use App\Http\Requests\SetAdvertisementTopCountryRequest;
use App\Http\Requests\SetAdvertisementUrgentRequest;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Price;
use App\Models\UserCategory;
use App\Models\ViewContactUser;
use App\Repositories\UserRepository;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


/**
 * Class TransactionBalanceApiController
 * @package App\Http\Controllers\Api
 * @group TransactionBalance
 */
class TransactionBalanceApiController extends Controller
{
    /**
     * @var TransactionBalanceHelper
     */
    private $transactionBalanceHelper;

    /**
     * @var CommonHelper
     */
    private $commonHelper;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(TransactionBalanceHelper $transactionBalanceHelper,
                                CommonHelper $commonHelper,
                                UserRepository $userRepository)
    {
        $this->transactionBalanceHelper = $transactionBalanceHelper;
        $this->commonHelper = $commonHelper;
        $this->userRepository = $userRepository;

    }


    /**
     * @return JsonResponse
     */
    public function setCompanyTop(): JsonResponse
    {
        $user = auth()->user();

        try {
            $company = $this->transactionBalanceHelper->getCompany($user);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $amount = 200.00;
        // todo обработку валюты
        $price = Price::where('key', 'company_top')->first();
        if (!empty($price))
            $amount = $price->amount;

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::COMPANY_TOP,
                'Продвижение компание ' . $company->name . ' #' . $company->id . '. Пакет Закрепление');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $date = $this->transactionBalanceHelper->addDays(7, $company->is_top_at);
        $company->is_top_at = $date;
        $company->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'package_name' => 'Закрепление',
            'date' => (new DateTime($date))->format('d.m.Y'),
            ]
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function setCompanyAllocate(): JsonResponse
    {
        $user = auth()->user();

        try {
            $company = $this->transactionBalanceHelper->getCompany($user);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $amount = 199.00;
        // todo обработку валюты
        $price = Price::where('key', 'company_allocate')->first();
        if (!empty($price))
            $amount = $price->amount;

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::COMPANY_ALLOCATE,
                'Продвижение компание ' . $company->name . ' #' . $company->id . '. Пакет Выделение');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $date = $this->transactionBalanceHelper->addDays(7, $company->is_allocate_at);
        $company->is_allocate_at = $date;
        $company->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'package_name' => 'Выделение',
            'date' => (new DateTime($date))->format('d.m.Y'),
            ]
        ]);
    }

    /**
     * @param SetAdvertisementTopCountryRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function setAdvertisementTopCountry(SetAdvertisementTopCountryRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $advertisement = $this->transactionBalanceHelper->getAdvertisement($user, $request->advertisement_id);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $amount = 190.00;
        // todo обработку валюты
        $price = Price::where('key', 'advertisement_top_country')->first();
        if (!empty($price))
            $amount = $price->amount;

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::ADVERTISEMENT_TOP_COUNTRY,
                'Продвижение объявления #' . $advertisement->id . '. Пакет Поднятие по всей стране');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $date = $this->transactionBalanceHelper->addDays(7, $advertisement->is_top_country_at);
        $advertisement->is_top_country_at = $date;
        $advertisement->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'package_name' => 'Поднятие по всей стране',
            'date' => (new DateTime($date))->format('d.m.Y'),
            ]
        ]);
    }

    /**
     * @param SetAdvertisementAllocateRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function setAdvertisementAllocate(SetAdvertisementAllocateRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $advertisement = $this->transactionBalanceHelper->getAdvertisement($user, $request->advertisement_id);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $amount = 199.00;
        // todo обработку валюты
        $price = Price::where('key', 'advertisement_allocate')->first();
        if (!empty($price))
            $amount = $price->amount;

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::ADVERTISEMENT_ALLOCATE,
                'Продвижение объявления #' . $advertisement->id . '. Пакет Выделение');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $date = $this->transactionBalanceHelper->addDays(7, $advertisement->is_allocate_at);
        $advertisement->is_allocate_at = $date;
        $advertisement->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'package_name' => 'Выделение',
            'date' => (new DateTime($date))->format('d.m.Y'),
            ]
        ]);
    }

    /**
     * @param SetAdvertisementUrgentRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function setAdvertisementUrgent(SetAdvertisementUrgentRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $advertisement = $this->transactionBalanceHelper->getAdvertisement($user, $request->advertisement_id);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }


        $amount = 187.00;
        // todo обработку валюты
        $price = Price::where('key', 'advertisement_urgent')->first();
        if (!empty($price))
            $amount = $price->amount;

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::ADVERTISEMENT_URGENT,
                'Продвижение объявления #' . $advertisement->id . '. Пакет Срочно');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $date = $this->transactionBalanceHelper->addDays(7, $advertisement->is_urgent_at);
        $advertisement->is_urgent_at = $date;
        $advertisement->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'package_name' => 'Срочно',
            'date' => (new DateTime($date))->format('d.m.Y'),
            ]
        ]);
    }

    /**
     * @param SetAdvertisementUrgentRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function setAdvertisementTurbo(SetAdvertisementUrgentRequest $request): JsonResponse
    {
        $user = auth()->user();

        try {
            $advertisement = $this->transactionBalanceHelper->getAdvertisement($user, $request->advertisement_id);
        } catch (\Throwable $throwable) {
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }


        $amount = 450.00;
        // todo обработку валюты
        $price = Price::where('key', 'advertisement_turbo')->first();
        if (!empty($price))
            $amount = $price->amount;

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::ADVERTISEMENT_TURBO,
                'Продвижение объявления #' . $advertisement->id . '. Пакет Турбо');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $advertisement->is_allocate_at = $this->transactionBalanceHelper->addDays(5, $advertisement->is_allocate_at);

        $date = $this->transactionBalanceHelper->addDays(15, $advertisement->is_urgent_at);
        $advertisement->is_urgent_at = $date;
        $advertisement->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'package_name' => 'Турбо',
            'date' => (new DateTime($date))->format('d.m.Y'),
            ]
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getAdvertisementPrices(): JsonResponse
    {
        return response()->json(['data' => Price::where('group', 'additional_advertisement')->get(['id', 'name', 'amount'])]);
    }

    /**
     * @return JsonResponse
     */
    public function getViewContactUserPrices(): JsonResponse
    {
        return response()->json(['data' => Price::where('group', 'view_contact_user')->orderBy('amount', 'ASC')->get(['id', 'name', 'amount'])]);
    }

    /**
     * @return JsonResponse
     */
    public function getViewContactUser(): JsonResponse
    {
        $viewContact = 0;

        $viewContactUser = ViewContactUser::where('user_id', auth()->id())->first();

        if (!empty($viewContactUser))
            $viewContact = $viewContactUser->view_contact;

        return response()->json(['data' => ['view_contact' => $viewContact]]);
    }

    /**
     * @return JsonResponse
     */
    public function increaseLimitViewContactUser(IncreaseLimitViewContactUserRequest $request)
    {
        $user = auth()->user();

        $price = Price::where('id', $request->price_id)
             ->where('group', 'view_contact_user')
            ->first();
        if (empty($price))
            return response()->json(["non_field_error" => [__("user.Price not found")]], 400);

        $quantity = $price->quantity;
        $amount = $price->amount;

        $viewContactUser = ViewContactUser::where('user_id', $user->id)->first();
        if (empty($viewContactUser))
            return response()->json(["non_field_error" => [__("user.View Contact User not found")]], 400);

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::INCREASE_LIMIT_VIEW_CONTACT_USER,
                'Увеличение лимита просмотра контактов на ' . $quantity);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        $viewContactUser->view_contact += $quantity;
        $viewContactUser->save();

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0,
            'quantity' => $quantity,
            ]
        ]);

    }


    /**
     * @return JsonResponse
     */
    public function increaseLimitAdvertisementCategory(IncreaseLimitAdvertisementCategoryRequest $request): JsonResponse
    {
        $user = auth()->user();

        $amount = 250.00;
        // todo обработку валюты
        if (!empty($request->advertisement_price_id))
            $price = Price::where('id', $request->advertisement_price_id)->first();
        else
            $price = Price::where('key', '1_advertisement')->first();

        $quantity = 1;
        if (!empty($price)) {
            $quantity = $price->quantity;
            $amount = $price->amount;
        }

        // максимальный лимит по двум категориям
        $period = Category::where('key', $request->category_key)
            ->get()
            ->sum('period');

        $allQuantity = $quantity;
        $userCategory = $this->userRepository->getUserCategories($user, $request->category_key)->first();

        if (!empty($userCategory))
            $allQuantity += $userCategory->limit;

        if ($allQuantity > $period)
            return response()->json(["non_field_error" => [__("user.Limit is exceeded")]], 400);

        if ((float)$user->balance < (float)$amount)
            return response()->json(["low_balance" => [
                'transaction_balance_id' => -1,
                'balance' => (float)$user->balance,
                'amount' => (float)($amount - $user->balance),
            ]]);

        try {
            $transactionBalanceId = $this->transactionBalanceHelper->changeUserBalance($user,
                -1 * $amount,
                TransactionBalanceTypeEnum::INCREASE_LIMIT_ADVERTISEMENT_CATEGORY,
                'Увеличение лимита объявлений для категории. Добавление ' . $price->name);
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(["non_field_error" => [$throwable->getMessage()]], 400);
        }

        UserCategory::create([
            'user_id' => $user->id,
            'category_key' => $request->category_key,
            'number_advertisement' => $quantity,
        ]);

        return response()->json(['data' => [
            'transaction_balance_id' => $transactionBalanceId,
            'balance' => (float)$user->balance,
            'amount' => 0]]);
    }

    /**
     * @authenticated
     *
     * @param GetTransactionBalanceForUserRequest $request
     * @return JsonResponse
     * @queryParam sort_type string required The sort type 'ASC' or 'DESC'. Example: 'ASC'
     * @queryParam sort_column_name string required The sort column name 'id', 'created_at', 'type', 'amount'. Example: 'id'
     * @queryParam number_items_page integer For paginate
     * @queryParam page integer  For paginate
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getTransactionBalanceForUser(GetTransactionBalanceForUserRequest $request): JsonResponse
    {
        $user = auth()->user();
        $transactionBalances = $this->transactionBalanceHelper->getTransactionBalances($user, $request->sort_type, $request->sort_column_name);

        $prePage = 10;
        if (!empty($request->number_items_page))
            $prePage = (int)$request->number_items_page;

        $query = [
            'sort_type' => $request->sort_type,
            'sort_column_name' => $request->sort_column_name,
        ];

        $paginator = $this->commonHelper->paginate($transactionBalances, $prePage, $request->page, ['path' => url('/api/v1/getTransactionBalanceForUser'), 'query' => $query]);

        return response()->json(['data' => $paginator]);
    }


}
