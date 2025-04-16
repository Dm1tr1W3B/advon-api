<?php


namespace App\Http\Controllers;


use App\Http\Helpers\GeoDBHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateContentController extends Controller
{
    /**
     * For Voyager FormFields
     * @param Request $request
     * @return JsonResponse
     */
    public function updateContent(Request $request)
    {
        $field_id = $request->get('field_id');
        $value = $request->get('value');
        $key = $request->get('key');

        session()->forget($field_id);
        session()->put($field_id, $value);
        return response()->json([$key => $value]);

    }

    /**
     * For Voyager FormField
     * @param Request $request
     * @return JsonResponse
     */
    public function regions(Request $request)
    {
        $prefix = $request->get('search');
        $geoDB = new GeoDBHelper();
        $res = [
            'results' => []
        ];
        $countries = $geoDB->getRegions(session('country'), 'ru', $prefix);
        $res['results'] = $countries->map(function ($item) {
            return [
                'id' => $item->code,
                'text' => $item->name
            ];
        });
        return response()->json($res);
    }

    public function cities(Request $request)
    {
        $prefix = $request->get('search');

        $geoDB = new GeoDBHelper();
        $res = [
            'results' => []
        ];
        $countries = $geoDB->getCities(session('country'), session('region'), 'ru', $prefix);
        $res['results'] = $countries->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name
            ];
        });
        return response()->json($res);
    }

    public function countries(Request $request)
    {

        $prefix = $request->get('search');
        $geoDB = new GeoDBHelper();
        $res = [
            'results' => []
        ];
        $countries = $geoDB->getCountries('ru', $prefix);
        $res['results'] = $countries->map(function ($item) {
            return [
                'id' => $item->code,
                'text' => $item->name
            ];
        });
        return response()->json($res);
    }
}
