<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\LanguageHelper;
use App\Http\Requests\FeedbackSendEmailRequest;
use App\Http\Requests\StoreFeedbackRequest;
use App\Mail\FeedbackEmail;
use App\Models\Feedback;
use App\Models\FeedbackType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Class FeedbackApiController
 * @package App\Http\Controllers\Api
 * @group Feedback
 */
class FeedbackApiController extends Controller
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
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function getFeedbackTypeList(): JsonResponse
    {
        $feedbackTypes = FeedbackType::orderBy('display_order', 'ASC')->get(['id', 'key']);
        $keys = $feedbackTypes->pluck('key')->unique()->values()->all();
        $translationNames = $this->languageHelper->getTranslations($keys, App::getLocale());

        $feedbackTypes->map(function ($feedbackTypes) use ($translationNames) {
            $feedbackTypes->cfeedback_type_id =  $feedbackTypes->id;
            $feedbackTypes->feedback_type_name = $translationNames[$feedbackTypes->key];

            unset($feedbackTypes->id, $feedbackTypes->key);
        });

        return response()->json(['data' => $feedbackTypes]);
    }

    /**
     * @authenticated
     *
     * @param StoreFeedbackRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function storeFeedback(StoreFeedbackRequest $request): JsonResponse
    {
        try {
            Feedback::create([
                'email' => $request->email,
                'name' => $request->name,
                'feedback_type_id'  => $request->feedback_type_id,
                'message'  => $request->message,
                'was_viewed' => false,
            ]);

        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('message.Message was not stored')]], 400);
        }

        return response()->json(['data' => ['message' => 'store_feedback']]);
    }

    /**
     * @param FeedbackSendEmailRequest $request
     * @return JsonResponse
     *
     * @responseFile status=400 storage/responses/error/400.json
     * @responseFile status=422 storage/responses/error/422.json
     */
    public function feedbackSendEmail(FeedbackSendEmailRequest $request)
    {
        try {
            Mail::to('advonme2019@gmail.com')
                ->send(new FeedbackEmail($request->email,
                    $request->name,
                    $request->feedback_type,
                    $request->message));
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
            return response()->json(['non_field_error' => [__('message.The message was not sent')]], 400);
        }

        return response()->json(['data' => ['message' => 'send_feedback']]);
    }


}
