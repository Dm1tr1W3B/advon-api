<?php


namespace App\Http\Helpers;

use App\Services\GeoDbService;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class GeoDBHelper
{
    /**
     * @var GeoDbService
     */
    private $geoDb;

    /**
     * GeoDBHelper constructor.
     */
    public function __construct()
    {
        $this->geoDb = new GeoDbService;
    }


    /**
     * @return GeoDbService
     */
    public function getGeoDb(): GeoDbService
    {
        return $this->geoDb;
    }

    /**
     * @param null $lang
     * @param null $prefix
     * @param array $params
     * @return Collection
     */
    public function getCountries($lang = null, $prefix = null, $params = [])
    {
        if (!$lang)
            $lang = App::getLocale();

        // todo комент убрать когда проплатят
        //if (empty($params))
            //$params = ['limit' => 100];

        $response = collect($this->geoDb->countries($prefix, $lang, $params)->getBodyData());

        return $response->sortBy('name');

    }

    /**
     * @param $countryCode
     * @param null $lang
     * @param null $prefix
     * @param array $params
     * @return Collection
     */
    public function getRegions($countryCode, $lang = null, $prefix = null, $params = [])
    {
        if (!$lang)
            $lang = App::getLocale();

        // todo комент убрать когда проплатят
        // if (empty($params))
            // $params = ['limit' => 100];

        $response = collect($this->geoDb->regions($countryCode, $prefix, $lang, $params)->getBodyData());

        return $response->sortBy('name');

    }

    /**
     * @param $countryCode
     * @param $regionCode
     * @param null $lang
     * @param null $prefix
     * @param array $params
     * @return Collection
     */
    public function getCities($countryCode, $regionCode, $lang = null, $prefix = null, $params = ['minPopulation' => '1'])
    {
        if (!$lang)
            $lang = App::getLocale();

        // todo комент убрать когда проплатят
        // if (empty($params['limit']))
            // $params['limit'] = 100;

        $response = collect($this->geoDb->cities($countryCode, $regionCode, $prefix, $lang, $params)->getBodyData());

        return $response->sortBy('name');

    }

    /**
     * @param $cityId
     * @param null $lang
     * @return Collection
     */
    public function getCityDetail($cityId, $lang = null)
    {
        if (!$lang)
            $lang = App::getLocale();
        $response = collect($this->geoDb->cityDetails($cityId, $lang)->body());

        return $response->sortBy('name');

    }

    /**
     * @param $cityId
     * @param null $lang
     * @return string
     */
    public function getCityName($cityId, $lang = null): string
    {
        if (!$lang)
            $lang = App::getLocale();

        $cityName = '';

        $response = $this->geoDb->cityDetails($cityId, $lang)->body();

        if (empty($response))
            return $cityName;

        $data = json_decode($response, true);

        if (empty($data))
            return $cityName;

        if (!empty($data['data']['name']))
            return $data['data']['name'];

        if (!empty($data['data']['city']))
            return $data['data']['city'];

        return $cityName;

    }

    /**
     * @param $countryId
     * @param null $lang
     * @return Collection
     */
    public function getCountryDetail($countryId, $lang = null)
    {
        if (!$lang)
            $lang = App::getLocale();
        $response = collect($this->geoDb->countryDetails($countryId, $lang)->body());

        return $response->sortBy('name');

    }

    public function getCountryName($countryId, $lang = null)
    {
        if (!$lang)
            $lang = App::getLocale();

        $countryName = '';

        // todo delete
        $countries = [
            'ru' => 'Россия',
            'ua' => 'Украина',
            'by' => 'Белоруссия'
        ];

        if ($lang =='ru' && !empty($countries[Str::lower($countryId)]))
            return $countries[Str::lower($countryId)];

        $response = $this->geoDb->countryDetails($countryId, $lang)->body();

        if (empty($response))
            return $countryName;

        $data = json_decode($response, true);

        if (empty($data))
            return $countryName;

        if (!empty($data['data']['name']))
            return $data['data']['name'];

        return $countryName;
    }

    /**
     * @param $cityId
     * @param int $radius
     * @param null $lang
     * @return Collection
     */
    public function getNearByCities($cityId, $radius = 100, $lang = null)
    {
        if (!$lang)
            $lang = App::getLocale();
        $response = collect($this->geoDb->nearbyCities($cityId, $lang, $radius)->body());

        return $response->sortBy('name');

    }

    /**
     * @param $lat
     * @param $long
     * @param int $radius
     * @param null $lang
     * @return Collection
     * @throws Exception
     */
    public function getLocationNearbyCities($lat, $long, $radius = 40, $lang = null)
    {

        if (!$lang)
            $lang = App::getLocale();
        try {
            $body = $this->geoDb->locationNearbyCities($lat, $long, $lang, $radius)->body();

            $body = json_decode($body);

            if (empty($body->data))
                return collect([]);

            foreach ($body->data as $item) {
                if (Str::contains($item->region, Str::limit($item->name, 4, '')))      //($item->name == $item->region)
                    return collect([])->push($item);
            }

            $response = collect($body->data);
            return $response->sortBy('name');

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param $longitude
     * @param $latitude
     * @return array
     * @throws Exception
     */
    public function generateGeoDbDataByCoordinates($longitude, $latitude)
    {

        try {
            $data = $this->getLocationNearbyCities($latitude, $longitude)->first();
            if ($data)
                return [
                    'country' => $data->country,
                    'region' => $data->region,
                    'city' => $data->city,
                    'country_id' => $data->countryCode,
                    'region_id' => $data->regionCode,
                    'city_id' => $data->id,
                ];
            return [];
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

    }

    public function GroupByFirstLatter($collection)
    {
        $result = [];
        foreach ($collection as $r) {
            $key = strtoupper(mb_substr($r->name, 0, 1, "UTF-8"));
            if ($this->isNeedLang($key)) {
                if (!array_key_exists($key, $result)) {
                    $result[$key] ['letter'] = $key;
                    $result[$key] ['group'] =[];
                }

                array_push($result[$key]['group'], $r);
            }
        }
        return $result;
    }

    public function isNeedLang($text)
    {
        $lang = app()->getLocale();
        if ($lang == 'ru')
            return preg_match('/[\p{Cyrillic}]/u', $text);

        return true;
    }

    /**
     * @param null $countryCode
     * @param null $cityCode
     * @return array
     */
    public function getCountryAmdCityName($countryCode = null, $cityCode= null)
    {
        $city = '';
        $country = '';

        try {
            if (!empty($cityCode))
                $city = $this->getCityName($cityCode, App::getLocale());

            if (!empty($countryCode))
                $country = $this->getCountryName($countryCode, App::getLocale());

        } catch (Exception $exception) {
            Log::critical("GEODB Error", $exception->getTrace());
        }

        return [$country, $city];
    }
}
