<?php


namespace App\Http\Controllers;


use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\CompanyHelper;
use App\Http\Helpers\GeoDBHelper;
use App\Http\Helpers\ImageHelper;
use App\Http\Validators\CompanyValidator;
use App\Models\Company;
use App\Models\CompanyImage;
use App\Models\Image;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Rules\Phone;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class CompaniesController extends VoyagerBaseController
{

    /**
     * @var CompanyHelper
     */
    private $companyHelper;

    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    /**
     * @var CompanyValidator
     */
    private $companyValidator;

    public function __construct(CompanyHelper $companyHelper,
                                GeoDBHelper $GeoDBHelper,
                                CompanyValidator $companyValidator)
    {
        $this->companyHelper = $companyHelper;
        $this->GeoDBHelper = $GeoDBHelper;
        $this->companyValidator = $companyValidator;
    }

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        $searchNames = [];
        if ($dataType->server_side) {
            $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
                return [$row['field'] => $row->getTranslatedAttribute('display_name')];
            });
        }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            $query = $model::select($dataType->name.'.*');

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query->{$dataType->scope}();
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';

                $searchField = $dataType->name.'.'.$search->key;
                if ($row = $this->findSearchableRelationshipRow($dataType->rows->where('type', 'relationship'), $search->key)) {
                    $query->whereIn(
                        $searchField,
                        $row->details->model::where($row->details->label, $search_filter, $search_value)->pluck('id')->toArray()
                    );
                } else {
                    if ($dataType->browseRows->pluck('field')->contains($search->key)) {
                        $query->where($searchField, $search_filter, $search_value);
                    }
                }
            }

            $row = $dataType->rows->where('field', $orderBy)->firstWhere('type', 'relationship');
            if ($orderBy && (in_array($orderBy, $dataType->fields()) || !empty($row))) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';

                if ($orderBy == 'owner_id') {
                    if (!empty($row)) {
                        $query->select([
                            $dataType->name.'.*'
                        ])->leftJoin(
                            $row->details->table.' as joined',
                            $dataType->name.'.'.$row->details->column,
                            'joined.'.$row->details->key
                        );
                    }

                    $dataTypeContent = call_user_func([
                        $query->orderBy('joined.email', $querySortOrder),
                        $getter,
                    ]);
                }
                else {
                    if (!empty($row)) {
                        $query->select([
                            $dataType->name.'.*',
                            'joined.'.$row->details->label.' as '.$orderBy,
                        ])->leftJoin(
                            $row->details->table.' as joined',
                            $dataType->name.'.'.$row->details->column,
                            'joined.'.$row->details->key
                        );
                    }

                    $dataTypeContent = call_user_func([
                        $query->orderBy($orderBy, $querySortOrder),
                        $getter,
                    ]);
                }
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::user()->can('delete', app($dataType->model_name))) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, $sortOrder ?? 'desc']];
        }

        // Define list of columns that can be sorted server side
        $sortableColumns = $this->getSortableColumns($dataType->browseRows);

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortableColumns',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn'
        ));
    }

    public function store(Request $request)
    {

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $this->companyValidator->insertUpdateValidator()->validate();

        if (!empty($request->phone) && !empty(Company::withTrashed()->where('phone', (int)$request->phone)->first()))
            Validator::make([], ['phone' => 'required'], ['phone.required' => __('validation.unique', ['attribute' => 'phone'])])->validate();

        if (!empty($request->email) && !empty(Company::withTrashed()->where('email', $request->email)->first()))
            Validator::make([], ['email' => 'required'], ['email.required' => __('validation.unique', ['attribute' => 'email'])])->validate();

        $user = User::find($request->owner_id);

        if (empty($user))
            Validator::make([], ['owner_id' => 'required'], ['owner_id.required' => 'Пользователь не найден'])->validate();

        $redirectBack = redirect()->back();

        if ($user->company)
            return $redirectBack->with(['message' => 'У пользователя уже есть компания', 'alert-type' => 'error',]);

        if (!empty($request->latitude) && !empty($request->longitude)) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        try {

            $audioUrl = '';
            if (isset($request->audio_url))
                $audioUrl = $this->companyHelper->uploadedFile('companies/audio', $request->file("audio_url"));

            $documentUrl = '';
            if (isset($request->document_url))
                $documentUrl = $this->companyHelper->uploadedFile('companies/document', $request->file("document_url"));


            $company = Company::create([
                'owner_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'description' => empty($request->description) ? '' : $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'hashtags' => $request->hashtags,
                'phone' => empty((int)$request->phone) ? null : (int)$request->phone,
                'country' => empty($data->country) ? '' : $data->country,
                'region' => empty($data->region) ? '' : $data->region,
                'city' => empty($data->city) ? '' : $data->city,
                'country_id' => empty($data->countryCode) ? '' : $data->countryCode,
                'region_id' => empty($data->regionCode) ? '' : $data->regionCode,
                'city_id' => empty($data->id) ? '' : $data->id,
                'site_url' => empty($request->site_url) ? '' : $request->site_url,
                'video_url' => empty($request->video_url) ? '' : $request->video_url,
                'document_url' => $documentUrl,
                'audio_url' => $audioUrl,
                'is_verification' => empty($request->is_verification) ? false : $request->is_verification,
            ]);

            $this->addOrUpdateAdditionalPhotos($request->additional_photos, $company->id);

            $companyImage = CompanyImage::where('company_id', $company->id)->first();
            if (!empty($companyImage))
                $company->photo_id = $companyImage->image_id;

            if (isset($request->logo_id)) {
                $company->logo_id = ImageHelper::createPhotoFromRequest('companies/company_logo', $request->file('logo_id'))->id;
            }

            $company->save();

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $redirectBack->with(['message' => __("company.Failed to create a company") . $throwable->getMessage(), 'alert-type' => 'error',]);

        }

        event(new BreadDataAdded($dataType, $company));

        $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        return $redirect->with([
            'message' => __('voyager::generic.successfully_added_new') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

//        $val = $this->validateBread($request->all(), $dataType->addRows,$slug)->validate();

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope' . ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $company = $model->withTrashed()->findOrFail($id);
        } else {
            $company = $model->findOrFail($id);
        }

        // Check permission
        $this->authorize('edit', $company);

        $this->companyValidator->insertUpdateValidator()->validate();

        if (!empty($request->phone) && !empty(Company::withTrashed()->where('phone', (int)$request->phone)->where('id', '!=', $company->id)->first()))
            Validator::make([], ['phone' => 'required'], ['phone.required' => __('validation.unique', ['attribute' => 'phone'])])->validate();

        if (!empty($request->email) && !empty(Company::withTrashed()->where('email', $request->email)->where('id', '!=', $company->id)->first()))
            Validator::make([], ['email' => 'required'], ['email.required' => __('validation.unique', ['attribute' => 'email'])])->validate();

        if (!empty($request->latitude) &&
            $request->latitude != $company->latitude &&
            !empty($request->longitude) &&
            $request->longitude != $company->longitude
        ) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        $redirectBack = redirect()->back();

        try {
            if (isset($request->audio_url)) {

                $path = json_decode($company->audio_url, true);
                if (!empty($path[0]['download_link']))
                    File::delete(public_path('storage/' . $path[0]['download_link']));

                $company->audio_url = $this->companyHelper->uploadedFile('companies/audio', $request->file("audio_url"));
            }

            if (isset($request->document_url)) {

                $path = json_decode($company->document_url, true);
                if (!empty($path[0]['download_link']))
                    File::delete(public_path('storage/' . $path[0]['download_link']));

                $company->document_url = $this->companyHelper->uploadedFile('companies/document', $request->file("document_url"));
            }


            $logoId = null;
            if (!empty($request->logo_id)) {
                $logoId = $company->logo_id;
                $company->logo_id = ImageHelper::createPhotoFromRequest('companies/company_logo', $request->file('logo_id'))->id;
            }


            $company->name = $request->name;
            $company->email = $request->email;
            $company->description = empty($request->description) ? '' : $request->description;
            $company->latitude = $request->latitude;
            $company->longitude = $request->longitude;
            $company->is_top_at = $request->is_top_at;
            $company->is_allocate_at = $request->is_allocate_at;
            $company->is_verification = empty($request->is_verification) ? false : $request->is_verification;

            if (!empty($data->country))
                $company->country = $data->country;
            if (!empty($data->region))
                $company->region = $data->region;
            if (!empty($data->city))
                $company->city = $data->city;

            if (!empty($data->countryCode))
                $company->country_id = $data->countryCode;
            if (!empty($data->regionCode))
                $company->region_id = $data->regionCode;
            if (!empty($data->id))
                $company->city_id = $data->id;

            $company->hashtags = $request->hashtags;
            $company->site_url = empty($request->site_url) ? '' : $request->site_url;
            $company->video_url = empty($request->video_url) ? '' : $request->video_url;

            $company->phone = empty((int)$request->phone) ? null : (int)$request->phone;

            $company->photo_id = null;

            $company->save();

            $this->addOrUpdateAdditionalPhotos($request->additional_photos, $company->id);

            $companyImage = CompanyImage::where('company_id', $company->id)->first();
            if (!empty($companyImage))
                $company->photo_id = $companyImage->image_id;

            if (!empty($logoId))
                Image::destroy($logoId);

            $company->save();

            event(new BreadDataUpdated($dataType, $company));


        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $redirectBack->with(['message' => __("company.Failed to update a company") . $throwable->getMessage(), 'alert-type' => 'error',]);

        }

        $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);

    }

    public function addOrUpdateAdditionalPhotos($additional_photos, $id)
    {
        $company = Company::find($id);
        $existedImages = new Collection();
        $imageForSave = new Collection();
        $newImageForSave = new Collection();


        if (!$additional_photos) {

            $company->photo_id = null;
            $company->save();

            Image::destroy($company->images->map(
                function ($item) {
                    return $item->id;
                }
            ));

            $company->images()->delete();
            return;
        }
        foreach ($company->images as $existedImage)
            $existedImages->push($existedImage->id);


        foreach ($additional_photos as $photo) {
//            dd($existedImages->values()->all(),$photo['id'],in_array($photo['id'],$existedImages->all()));
            if (isset($photo['id']) && in_array($photo['id'], $existedImages->all())) {
                $imageForSave->push($photo['id']);

            } else if (isset($photo['image'])) {
                $newImage = ImageHelper::createPhotoFromRequest('company_additional_photos', $photo['image']);
                $newImageForSave->push($newImage->id);

            }
        }


        $diff = $existedImages->diff($imageForSave);

        if ($diff->isNotEmpty())
            Image::destroy($diff->all());

        $company->images()->attach($newImageForSave);

//        $user->images->de
    }

    public function remove_media(Request $request)
    {
        try {
            // GET THE SLUG, ex. 'posts', 'pages', etc.
            $slug = $request->get('slug');

            // GET file name
            $filename = $request->get('filename');

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

            $image = Image::where(['photo_url' => $filename])->first();

            if (!empty($image)) {

                if ($data->photo_id == $image->id)
                    $data->photo_id = null;

                if ($data->logo_id == $image->id)
                    $data->logo_id = null;

                $data->save();
                $image->delete();

                return response()->json([
                    'data' => [
                        'status' => 200,
                        'message' => __('voyager::media.file_removed'),
                    ],
                ]);

            }

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

    public function saveFile()
    {

    }

}
