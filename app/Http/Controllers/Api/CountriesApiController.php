<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Helpers\AdvertisementHelper;
use App\Http\Helpers\GeoDBHelper;
use App\Http\Requests\CitiesRequest;
use App\Http\Requests\GetCountryAndCityRequest;
use App\Http\Requests\RegionRequest;
use App\Http\Resources\CountryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Class CountriesApiController
 * @package App\Http\Controllers\Api
 * @group Countries
 */
class CountriesApiController extends Controller
{
    /**
     * @var GeoDBHelper
     */
    private $geoDbHelper;

    /**
     * @var AdvertisementHelper
     */
    private $advertisementHelper;

    public function __construct(GeoDBHelper $geoDbHelper, AdvertisementHelper $advertisementHelper)
    {
        $this->geoDbHelper = $geoDbHelper;
        $this->advertisementHelper = $advertisementHelper;
    }

    /**
     * @return JsonResponse
     * @responseFile storage/responses/countries/getCountries.json
     */
    public function getCountries()
    {
        $countries = $this->advertisementHelper->getCountries()->sortBy(function ($item, $key) {
            return $item->name;
        });

        /*
        $countries = $this->geoDbHelper->getCountries()->sortBy(function ($item, $key) {
            return $item->name;
        });
        */
        return CountryResource::collection($countries)->response();
    }

    /**
     * @param RegionRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/countries/getRegions.json
     */
    public function getRegions(RegionRequest $request)
    {
        $countries = $this->geoDbHelper->getRegions($request->get('country_code'))->sortBy(function ($item, $key) {
            return $item->name;
        });
        return response()->json(["data"=>$countries->values()]);
    }

    /**
     * @param CitiesRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/countries/getCities.json
     */
    public function getCities(CitiesRequest $request)
    {
        $cities = $this->geoDbHelper->getCities($request->get('country_code'), $request->get('region_code'))->sortBy(function ($item, $key) {
            return $item->name;
        });

        return response()->json(["data"=>$cities->values()]);
    }

    /**
     * @param RegionRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/countries/getRegionsGrouped.json
     */
    public function getRegionsGrouped(RegionRequest $request)
    {
        $countries = $this->geoDbHelper->getRegions($request->get('country_code'))->sortBy(function ($item, $key) {
            return $item->name;
        });
        $grouped = $this->geoDbHelper->GroupByFirstLatter($countries);

        return response()->json($grouped);
    }

    /**
     * @param CitiesRequest $request
     * @return JsonResponse
     * @responseFile storage/responses/countries/getCitiesGrouped.json
     */
    public function getCitiesGrouped(CitiesRequest $request)
    {
        $cities = $this->geoDbHelper->getCities($request->get('country_code'), $request->get('region_code'))->sortBy(function ($item, $key) {
            return $item->name;
        });
        $grouped = $this->geoDbHelper->GroupByFirstLatter($cities);

        return response()->json($grouped);
    }

    /**
     * @param GetCountryAndCityRequest $request
     * @return JsonResponse
     *
     * @queryParam latitude
     * @queryParam longitude
     */
    public function getCountryAndCity(GetCountryAndCityRequest $request): JsonResponse
    {
        if ($request->latitude && $request->longitude) {
            try {
                $data = $this->geoDbHelper->getLocationNearbyCities($request->latitude, $request->longitude, 10, 'ru')->first();
            } catch (\Exception $exception) {
                Log::critical("GEODB Error", $exception->getTrace());
            }
        }

        return response()->json(["data" => [
            'country' => empty($data->country) ? '' : $data->country,
            // 'region' => empty($data->region) ? '' : $data->region,
            'city' => empty($data->city) ? '' : $data->city,
            'country_code' => empty($data->countryCode) ? '' : $data->countryCode,
            // 'region_code' => empty($data->regionCode) ? '' : $data->regionCode,
            'city_code' => empty($data->id) ? '' : $data->id,
        ]]);
    }


}
