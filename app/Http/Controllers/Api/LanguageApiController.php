<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Helpers\LanguageHelper;
use App\Http\Requests\GetTranslationsRequest;
use App\Http\Resources\LangsResource;
use App\Models\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

/**
 * Class LanguageApiController
 * @package App\Http\Controllers\Api
 * @group Language
 */
class LanguageApiController extends Controller
{

    /**
     * @var LanguageHelper
     */
    private $languageHelper;

    public function __construct(LanguageHelper $languageHelper)
    {
        $this->languageHelper = $languageHelper;
    }

    /**
     * @return JsonResponse
     */
    public function getLanguages(): JsonResponse
    {
        $languages = Language::withTranslations()->where('enabled', true)->get();
        $translated_languages = $languages->translate(app()->getLocale());
        try {
            return LangsResource::collection($translated_languages)->additional(
                ['locale'=>App::getLocale()]
            )->response();
        } catch (\Throwable $throwable) {
            return response()->json(['non_field_error' => [$throwable->getMessage()]], 400);
        }
    }

    /**
     * @param GetTranslationsRequest $request
     * @return JsonResponse
     */
    public function getTranslations(GetTranslationsRequest $request): JsonResponse
    {
        return response()->json(['data' =>
            $this->languageHelper->getTranslations($request->keys, $request->language_key)]);
    }

}
