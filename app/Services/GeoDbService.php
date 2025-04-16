<?php


namespace App\Services;


use Exception;
use fGalvao\BaseClientApi\HttpClient;
use fGalvao\BaseClientApi\Response;
use fGalvao\GeoDB\GeoDB;
use Illuminate\Support\Facades\Http;

class GeoDbService
{
    /**
     * @var string
     */
    static protected $base_url = 'https://wft-geo-db.p.rapidapi.com/';
    /**
     * @var string
     */
    static protected $api_host = 'wft-geo-db.p.rapidapi.com';

    /**
     * @var bool
     */
    static protected $dev_mode = true;

    /**
     * @var GeoDB
     */
    protected $geoDB;
    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * GeoDbService constructor.
     * @throws Exception
     */
    public function __construct()
    {

        $this->api_key = env('GEODB_API_KEY', 'c973275c56msh33bc5e118971b0fp11982bjsnb4f3abb7e866');
        if (empty($this->api_key))
            throw new Exception('GEODB_API_KEY not set in env');

        $settings = [
            'BASE_URL' => self::$base_url,
            'API_HOST' => self::$api_host,
            'API_KEY' => $this->api_key,
            'DEV_MODE' => self::$dev_mode,
        ];

        $clientSetting = GeoDB::buildClientSettings($settings);
        $this->client = new HttpClient($clientSetting);
        $this->geoDB = new GeoDB($this->client);

    }

    /**
     * @param $prefix
     * @param $lang
     * @param $params
     * @return Response
     */
    public function countries($prefix, $lang, $params)
    {
        return $this->geoDB->countries($prefix, $lang, $params);
    }

    /**
     * @param $countryCode
     * @param $prefix
     * @param $lang
     * @param $params
     * @return Response
     */
    public function regions($countryCode, $prefix, $lang, $params)
    {
        return $this->geoDB->regions($countryCode, $prefix, $lang, $params);

    }

    /**
     * @param $countryCode
     * @param $regionCode
     * @param $prefix
     * @param $lang
     * @param $params
     * @return Response
     */
    public function cities($countryCode, $regionCode, $prefix, $lang, $params)
    {
        return $this->geoDB->cities($countryCode, $regionCode, $prefix, $lang, $params);

    }

    /**
     * @param $cityId
     * @param $lang
     * @return \Illuminate\Http\Client\Response
     */
    public function cityDetails($cityId, $lang)
    {
        $params = [];
        $uri = 'v1/geo/cities/%s';
        $uri = sprintf($uri, $cityId);

        if ($lang)
            $params['languageCode'] = $lang;

        return $this->get($uri, $params);

    }

    /**
     * @param $countryId
     * @param $lang
     * @return \Illuminate\Http\Client\Response
     */
    public function countryDetails($countryId, $lang)
    {
        $params = [];
        $uri = 'v1/geo/countries/%s';
        $uri = sprintf($uri, $countryId);

        if ($lang)
            $params['languageCode'] = $lang;

        return $this->get($uri, $params);

    }

    /**
     * @param $cityId
     * @param $lang
     * @param $radius
     * @return \Illuminate\Http\Client\Response
     */
    public function nearbyCities($cityId, $lang, $radius)
    {
        $params = [];
        $uri = '/v1/geo/cities/%s/nearbyCities';
        $uri = sprintf($uri, $cityId);

        if ($lang)
            $params['languageCode'] = $lang;
        if ($radius)
            $params['radius'] = $radius;
        return $this->get($uri, $params);

    }

    /**
     * @param $lat
     * @param $long
     * @param $lang
     * @param $radius
     * @return \Illuminate\Http\Client\Response
     * @throws Exception
     */
    public function locationNearbyCities($lat, $long, $lang, $radius)
    {
        $params = [];
        $uri = '/v1/geo/locations/%s/nearbyCities';
        if ($long > 0)
            $sign = "+";
        else
            $sign = "";
        $uri = sprintf($uri, $lat . $sign . $long);

        // todo комент убрать когда проплатят
        // $params['limit'] = 50;
        $params['types'] = 'CITY';

        if ($lang)
            $params['languageCode'] = $lang;
        if ($radius)
            $params['radius'] = $radius;
        try {


            return $this->get($uri, $params);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $uri
     * @param $params
     * @return \Illuminate\Http\Client\Response
     * @throws Exception
     */
    protected function get($uri, $params)
    {
        try {
            return Http::withHeaders([
                'X-Rapidapi-Key' => $this->api_key,
                'X-Rapidapi-Host' => self::$api_host

            ])->get(self::$base_url . $uri, $params);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
