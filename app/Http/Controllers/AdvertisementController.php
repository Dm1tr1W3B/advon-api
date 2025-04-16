<?php


namespace App\Http\Controllers;

use App\Http\Helpers\GeoDBHelper;
use App\Http\Helpers\ImageHelper;
use App\Models\Advertisement;
use App\Models\AdvertisementImage;
use App\Models\Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class AdvertisementController extends VoyagerBaseController
{
    /**
     * @var GeoDBHelper
     */
    private $GeoDBHelper;

    public function __construct(GeoDBHelper $GeoDBHelper)
    {
        $this->GeoDBHelper = $GeoDBHelper;
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

            $new = false;
            if ($request->get('new')) {
                $new = true;
                $query = $query->where('is_moderate', false);
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
                if ($orderBy == 'user_id') {
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
                elseif ($orderBy == 'company_id' || $orderBy == 'category_id') {
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
                        $query->orderBy('joined.name', $querySortOrder),
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
            'showCheckboxColumn',
            'new'
        ));
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
            $advertisement = $model->withTrashed()->findOrFail($id);
        } else {
            $advertisement = $model->findOrFail($id);
        }

        // Check permission
        $this->authorize('edit', $advertisement);

        if (!empty($request->latitude) &&
            $request->latitude != $advertisement->latitude &&
            !empty($request->longitude) &&
            $request->longitude != $advertisement->longitude
        ) {
            try {
                $data = $this->GeoDBHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        $redirectBack = redirect()->back();

        try {

            $advertisement->title = $request->title;
            $advertisement->description = $request->description;
            $advertisement->currency_id = $request->currency_id;
            $advertisement->price_type = $request->price_type;
            $advertisement->payment = $request->payment;
            $advertisement->hashtags =  !empty($request->hashtags) ? $request->hashtags : [];

            $advertisement->travel_abroad =  $request->travel_abroad;
            $advertisement->ready_for_political_advertising =  $request->ready_for_political_advertising;
            $advertisement->photo_report =  $request->photo_report;
            $advertisement->make_and_place_advertising =  $request->make_and_place_advertising;
            $advertisement->reach_audience =  $request->reach_audience;
            $advertisement->is_published =  !empty($request->is_published) ? $request->is_published : false;
            $advertisement->published_at =  $request->published_at;
            $advertisement->is_hide =  !empty($request->is_hide) ? $request->is_hide : false;
            $advertisement->is_allocate_at =  $request->is_allocate_at;
            $advertisement->is_top_country_at =  $request->is_top_country_at;
            $advertisement->is_urgent_at =  $request->is_urgent_at;

            $advertisement->latitude = $request->latitude;
            $advertisement->longitude = $request->longitude;

            if (!empty($data->country))
                $advertisement->country = $data->country;
            if (!empty($data->region))
                $advertisement->region = $data->region;
            if (!empty($data->city))
                $advertisement->city = $data->city;

            if (!empty($data->countryCode))
                $advertisement->country_ext_code = $data->countryCode;
            if (!empty($data->regionCode))
                $advertisement->region_ext_code = $data->regionCode;
            if (!empty($data->id))
                $advertisement->city_ext_code = $data->id;


            $advertisement->save();

            $this->addOrUpdateAdditionalPhotos($request->additional_photos, $advertisement->id);

            $advertisementImage = AdvertisementImage::where('advertisement_id', $advertisement->id)
                ->orderBy('image_id', 'ASC')
                ->first();
            if (!empty($advertisementImage)) {
                $advertisement->photo_id = $advertisementImage->image_id;
                $advertisement->save();
            }


            event(new BreadDataUpdated($dataType, $advertisement));


        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return $redirectBack->with(['message' => __("advertisement.Failed to update a advertisement") . $throwable->getMessage(), 'alert-type' => 'error',]);

        }

        $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        return $redirect->with([
            'message' => __('voyager::generic.successfully_updated') . " {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);

    }

    public function addOrUpdateAdditionalPhotos($additional_photos, $id)
    {
        $advertisement = Advertisement::find($id);
        $existedImages = new Collection();
        $imageForSave = new Collection();
        $newImageForSave = new Collection();


        if (!$additional_photos) {

            $advertisement->photo_id = null;
            $advertisement->save();

            Image::destroy($advertisement->images->map(
                function ($item) {
                    return $item->id;
                }
            ));

            $advertisement->images()->delete();
            return;
        }
        foreach ($advertisement->images as $existedImage)
            $existedImages->push($existedImage->id);


        foreach ($additional_photos as $photo) {
//            dd($existedImages->values()->all(),$photo['id'],in_array($photo['id'],$existedImages->all()));
            if (isset($photo['id']) && in_array($photo['id'], $existedImages->all())) {
                $imageForSave->push($photo['id']);

            } else if (isset($photo['image'])) {
                $newImage = ImageHelper::createPhotoFromRequest('product_additional_photos', $photo['image']);
                $newImageForSave->push($newImage->id);

            }
        }


        $diff = $existedImages->diff($imageForSave);

        if ($diff->isNotEmpty())
            Image::destroy($diff->all());

        $advertisement->images()->attach($newImageForSave);

//        $user->images->de
    }


    public function moderate(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Init array of IDs
        $ids = [];
        if ((int) $id < 0) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }

        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

            // Check permission
            $this->authorize('moderate', $data);

            $model = app($dataType->model_name);
            if (!($model && in_array(SoftDeletes::class, class_uses_recursive($model)))) {
                $this->cleanup($dataType, $data);
            }
        }

        $displayName = count($ids) > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

        try {
            $data->whereIn('id', $ids)->update(['is_moderate' => true]);
            $res = true;
        } catch (\Exception $exception) {
            $res = false;
        }


        $data = $res
            ? [
                'message'    => 'Объявление успешно промодерировано',
                'alert-type' => 'success',
            ]
            : [
                'message'    => 'Объявление не промодерировано',
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
    }
}
