<?php


namespace App\Http\Controllers;


use App\Http\Enums\TransactionBalanceTypeEnum;
use App\Http\Helpers\GeoDBHelper;
use App\Http\Helpers\ImageHelper;
use App\Http\Helpers\LanguageHelper;
use App\Http\Helpers\TransactionBalanceHelper;
use App\Http\Requests\UpdateBalanceRequest;
use App\Http\Validators\UserValidator;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerUserController;

class UserController extends VoyagerUserController
{

    /**
     * @var UserValidator
     */
    private $userValidator;

    /**
     * @var TransactionBalanceHelper
     */
    private $transactionBalanceHelper;

    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    public function __construct(UserValidator $userValidator,
                                TransactionBalanceHelper $transactionBalanceHelper,
                                GeoDBHelper $GeoDBHelper,
                                LanguageHelper $languageHelper)
    {
        $this->userValidator = $userValidator;
        $this->transactionBalanceHelper = $transactionBalanceHelper;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->languageHelper = $languageHelper;
    }

    public function profile(Request $request)
    {
        $route = '';
        $dataType = Voyager::model('DataType')->where('model_name', Auth::guard(app('VoyagerGuard'))->getProvider()->getModel())->first();
        if (!$dataType && app('VoyagerGuard') == 'web') {
            $route = route('voyager.users.edit', Auth::user()->getKey());
        } elseif ($dataType) {
            $route = route('voyager.'.$dataType->slug.'.edit', Auth::user()->getKey());
        }

        return Voyager::view('voyager::profile', compact('route'));
    }

    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $request->merge(['phone' => (int)$request->phone]);

        $this->userValidator->insertUpdateValidator()->validate();

        if (!empty($request->phone) && !empty(User::withTrashed()->where('phone', (int)$request->phone)->first()))
            Validator::make([], ['phone' => 'required'], ['phone.required' => __('validation.unique', ['attribute' => 'phone'])])->validate();

        if (!empty($request->email) && !empty(User::withTrashed()->where('email', $request->email)->first()))
            Validator::make([], ['email' => 'required'], ['email.required' => __('validation.unique', ['attribute' => 'email'])])->validate();


        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax

        $coordinates= clone $dataType->addRows->where('field','coordinates')->first();
        $coordinates->type='text';
        $coordinates->field='longitude';
        $dataType->addRows->push($coordinates);
        $coordinates= clone $dataType->addRows->where('field','coordinates')->first();
        $coordinates->id=10;
        $coordinates->type='text';
        $coordinates->field='latitude';
        $dataType->addRows->push($coordinates);

        $filtered = $dataType->addRows->filter(function ($value, $key) {
            $arr = ["additional_photos",'coordinates'];
            if (!in_array($value->field, $arr))
                return $value;
        });
        //MY
//        dd($request->all(),$data,$filtered);
        // Validate fields with ajax



        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
//        dd($request->all(),$filtered);

        $data = $this->insertUpdateData($request, $slug, $filtered, new $dataType->model_name());


        $this->addOrUpdateAdditionalPhotos($request->additional_photos, $data->id);

        if (!empty($data->latitude) && !empty($data->longitude)) {
            try {

                $dataGeo = $this->GeoDBHelper->getLocationNearbyCities($request->latitude,  $request->longitude,  10, 'ru')->first();

                $user = User::find($data->id);
                if (!empty($dataGeo->country))
                    $user->country = $dataGeo->country;
                if (!empty($dataGeo->region))
                    $user->region = $dataGeo->region;
                if (!empty($dataGeo->city))
                    $user->city = $dataGeo->city;

                if (!empty($dataGeo->countryCode))
                    $user->country_id = $dataGeo->countryCode;
                if (!empty($dataGeo->regionCode))
                    $user->region_id = $dataGeo->regionCode;
                if (!empty($dataGeo->id))
                    $user->city_id = $dataGeo->id;

                $user->save();

            } catch (\Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }



        event(new BreadDataAdded($dataType, $data));




        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }
    public function update(Request $request, $id)
    {


        $slug = $this->getSlug($request);

        $request->merge(['phone' => (int)$request->phone]);

        $this->userValidator->insertUpdateValidator()->validate();

        if (!empty($request->phone) && !empty(User::withTrashed()->where('phone', (int)$request->phone)->where('id', '!=', $id)->first()))
            Validator::make([], ['phone' => 'required'], ['phone.required' => __('validation.unique', ['attribute' => 'phone'])])->validate();

        if (!empty($request->email) && !empty(User::withTrashed()->where('email', $request->email)->where('id', '!=', $id)->first()))
            Validator::make([], ['email' => 'required'], ['email.required' => __('validation.unique', ['attribute' => 'email'])])->validate();


        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();


        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();


        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = $model->findOrFail($id);
        }

        // Check permission
        $this->authorize('edit', $data);

        //MY FILTERED


        $coordinates= clone $dataType->editRows->where('field','coordinates')->first();
        $coordinates->type='text';
        $coordinates->field='longitude';
        $dataType->editRows->push($coordinates);
        $coordinates= clone $dataType->editRows->where('field','coordinates')->first();
        $coordinates->id=10;
        $coordinates->type='text';
        $coordinates->field='latitude';
        $dataType->editRows->push($coordinates);

        $filtered = $dataType->editRows->filter(function ($value, $key) {
            $arr = ["additional_photos",'coordinates'];
            if (!in_array($value->field, $arr))
                return $value;
        });
        //MY
//        dd($request->all(),$data,$filtered);
        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $filtered, $data);

        $this->addOrUpdateAdditionalPhotos($request->additional_photos, $data->id);


        if (!empty($data->latitude) && !empty($data->longitude)) {
            try {

                $dataGeo = $this->GeoDBHelper->getLocationNearbyCities($request->latitude,  $request->longitude,  10, 'ru')->first();

                $user = User::find($data->id);
                if (!empty($dataGeo->country))
                    $user->country = $dataGeo->country;
                if (!empty($dataGeo->region))
                    $user->region = $dataGeo->region;
                if (!empty($dataGeo->city))
                    $user->city = $dataGeo->city;

                if (!empty($dataGeo->countryCode))
                    $user->country_id = $dataGeo->countryCode;
                if (!empty($dataGeo->regionCode))
                    $user->region_id = $dataGeo->regionCode;
                if (!empty($dataGeo->id))
                    $user->city_id = $dataGeo->id;

                $user->save();

            } catch (\Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }


        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function addOrUpdateAdditionalPhotos($additional_photos, $id)
    {
        $user = User::find($id);
        $existedImages = new Collection();
        $imageForSave = new Collection();
        $newImageForSave = new Collection();

        if (!$additional_photos) {
            return Image::destroy($user->images->map(
                function ($item) {
                    return $item->id;
                }
            ));

        }
        foreach ($user->images as $existedImage)
            $existedImages->push($existedImage->id);


        foreach ($additional_photos as $photo) {
//            dd($existedImages->values()->all(),$photo['id'],in_array($photo['id'],$existedImages->all()));
            if (isset($photo['id']) && in_array($photo['id'], $existedImages->all())) {
                $imageForSave->push($photo['id']);

            } else if (isset($photo['image'])) {
                $newImage=ImageHelper::createPhotoFromRequest('user_additional_photos',$photo['image']);
                $newImageForSave->push($newImage->id);

            }
        }


        $diff = $existedImages->diff($imageForSave);

        if ($diff->isNotEmpty())
            Image::destroy($diff->all());

        $user->images()->attach($newImageForSave);

//        $user->images->de
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function changeBalance(int $id)
    {
        $userBalance = User::find($id);

        if (empty($userBalance))
            return redirect()->route("voyager.users.index")->with([
                'message' => 'Пользователь не найден',
                'alert-type' => 'error',
            ]);

        $transactionBalanceTypes = $this->languageHelper->getTranslations([
            TransactionBalanceTypeEnum::KEYS[TransactionBalanceTypeEnum::ADMIN_CHANGE_BALANCE],
            TransactionBalanceTypeEnum::KEYS[TransactionBalanceTypeEnum::ADMIN_BONUS_REGISTRATION_REAL],
            TransactionBalanceTypeEnum::KEYS[TransactionBalanceTypeEnum::ADMIN_BONUS_REFERRAL_REAL],
        ] , App::getLocale());

        $types = [
            [
              'id' => TransactionBalanceTypeEnum::ADMIN_CHANGE_BALANCE,
              'name' =>  $transactionBalanceTypes[TransactionBalanceTypeEnum::KEYS[TransactionBalanceTypeEnum::ADMIN_CHANGE_BALANCE]]
            ],
            [
                'id' => TransactionBalanceTypeEnum::ADMIN_BONUS_REGISTRATION_REAL,
                'name' =>  $transactionBalanceTypes[TransactionBalanceTypeEnum::KEYS[TransactionBalanceTypeEnum::ADMIN_BONUS_REGISTRATION_REAL]]
            ],
            [
                'id' => TransactionBalanceTypeEnum::ADMIN_BONUS_REFERRAL_REAL,
                'name' =>  $transactionBalanceTypes[TransactionBalanceTypeEnum::KEYS[TransactionBalanceTypeEnum::ADMIN_BONUS_REFERRAL_REAL]]
            ],
        ];

        return view('vendor.voyager.users.change-balance', ['data' => ['userBalance' => $userBalance,  'types' => $types]]);

    }

    /**
     * @param UpdateBalanceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBalance(UpdateBalanceRequest $request)
    {
        $userBalance = User::find($request->user_id);

        if (empty($userBalance))
            return redirect()->route("voyager.users.index")->with([
                'message' => 'Пользователь не найден',
                'alert-type' => 'error',
            ]);

        try {
             $this->transactionBalanceHelper->changeUserBalance($userBalance,
                 $request->amount,
                 $request->type_id,
                'Изменение баланса администратором');
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());

            return redirect()->route("voyager.users.index")->with([
                'message' => $throwable->getMessage(),
                'alert-type' => 'error',
            ]);

        }

        return redirect()->route("voyager.users.index")->with([
            'message' => 'Баланс изменен',
            'alert-type' => 'success',
        ]);
    }

    public function changeBlock(int $id)
    {
        $userBlock = User::find($id);

        if (empty($userBlock))
            return redirect()->back()->with([
                'message' => 'Пользователь не найден',
                'alert-type' => 'error',
            ]);

        $userBlock->blocked = !$userBlock->blocked;
        $userBlock->save();

        return redirect()->back()->with([
            'message' => 'Блок изменен',
            'alert-type' => 'success',
        ]);

    }

    public function remove_media(Request $request)
    {

        try {
            // GET THE SLUG, ex. 'posts', 'pages', etc.
            $slug = $request->get('slug');

            // GET file name
            $filename = $request->get('filename');

            if ($filename = 'users/default.png')
                return response()->json([
                    'data' => [
                        'status'  => 500,
                        'message' => __('voyager::media.file_does_not_exist'),
                    ],
                ]);

            // GET record id
            $id = $request->get('id');

            // GET field name
            $field = $request->get('field');

            // GET multi value
            $multi = $request->get('multi');

            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Load model and find record
            $model = app($dataType->model_name);
            $data = $model::find([$id])->first();

            // Check if field exists
            if (!isset($data->{$field})) {
                throw new Exception(__('voyager::generic.field_does_not_exist'), 400);
            }

            // Check permission
            $this->authorize('edit', $data);

            if (@json_decode($multi)) {
                // Check if valid json
                if (is_null(@json_decode($data->{$field}))) {
                    throw new Exception(__('voyager::json.invalid'), 500);
                }

                // Decode field value
                $fieldData = @json_decode($data->{$field}, true);
                $key = null;

                // Check if we're dealing with a nested array for the case of multiple files
                if (is_array($fieldData[0])) {
                    foreach ($fieldData as $index=>$file) {
                        // file type has a different structure than images
                        if (!empty($file['original_name'])) {
                            if ($file['original_name'] == $filename) {
                                $key = $index;
                                break;
                            }
                        } else {
                            $file = array_flip($file);
                            if (array_key_exists($filename, $file)) {
                                $key = $index;
                                break;
                            }
                        }
                    }
                } else {
                    $key = array_search($filename, $fieldData);
                }

                // Check if file was found in array
                if (is_null($key) || $key === false) {
                    throw new Exception(__('voyager::media.file_does_not_exist'), 400);
                }

                $fileToRemove = $fieldData[$key]['download_link'] ?? $fieldData[$key];

                // Remove file from array
                unset($fieldData[$key]);

                // Generate json and update field
                $data->{$field} = empty($fieldData) ? null : json_encode(array_values($fieldData));
            } else {
                if ($filename == $data->{$field}) {
                    $fileToRemove = $data->{$field};

                    $data->{$field} = null;
                } else {
                    throw new Exception(__('voyager::media.file_does_not_exist'), 400);
                }
            }

            $row = $dataType->rows->where('field', $field)->first();

            // Remove file from filesystem
            if (in_array($row->type, ['image', 'multiple_images'])) {
                $this->deleteBreadImages($data, [$row], $fileToRemove);
            } else {
                $this->deleteFileIfExists($fileToRemove);
            }

            $data->save();

            return response()->json([
                'data' => [
                    'status'  => 200,
                    'message' => __('voyager::media.file_removed'),
                ],
            ]);
        } catch (Exception $e) {
            $code = 500;
            $message = __('voyager::generic.internal_error');

            if ($e->getCode()) {
                $code = $e->getCode();
            }

            if ($e->getMessage()) {
                $message = $e->getMessage();
            }

            return response()->json([
                'data' => [
                    'status'  => $code,
                    'message' => $message,
                ],
            ], $code);
        }
    }

}
