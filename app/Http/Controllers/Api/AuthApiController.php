<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\AuthHelper;
use App\Http\Helpers\UserHelper;
use App\Http\Requests\CreateEmailForSocialUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\SocialUser;
use App\Repositories\UserRepository;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User;
use TCG\Voyager\Models\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Class AuthApiController
 * @package App\Http\Controllers\Api
 * @group Auth
 */
class AuthApiController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserHelper
     */
    private $userHelper;
    /**
     * @var AuthHelper
     */
    private $authHelper;

    public function __construct(UserRepository $userRepository, UserHelper $userHelper, AuthHelper $authHelper)
    {
        $this->userRepository = $userRepository;
        $this->userHelper = $userHelper;
        $this->authHelper = $authHelper;
    }


    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {

        $user = $this->authHelper->getUser();
        if (!empty($user)) {
            return response()->json(['data' => [
                'token' => JWTAuth::refresh(),
                'token_time_expired' => time() + 60 * config('jwt.ttl'),
                'user' => (new UserResource($user))->jsonSerialize()
            ]]);
        }

        if (empty($request->login) || empty($request->password))
            return response()->json(['non_field_error' => [__('auth.Login or password cannot be empty')]], 400);

        $role = Role::where('name', 'user')->first();

        if (empty($role))
            return response()->json(['non_field_error' => [__('user.Role not found')]], 400);

        $credentials = ['email' => $request->login, 'password' => $request->password, 'role_id' => $role->id];

        if (stripos($request->login, '@') === false)
            $credentials = ['phone' => (int)$request->login, 'password' => $request->password, 'role_id' => $role->id];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['non_field_error' => [__('auth.failed')]], 400);
            }
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            return response()->json(['non_field_error' => [$e->getMessage()]], 400);
        }

        $user = auth()->user();

        if (!empty($user->blocked))
            return response()->json(['non_field_error' => [__('auth.The user blocked')]], 400);

        $createdAt = new DateTime($user->created_at);

        return response()->json(['data' => [
            'token' => $token,
            'token_time_expired' => time() + 60 * config('jwt.ttl'),
            'user' => (new UserResource($user))->jsonSerialize()
        ]]);
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return response()->json(['data' => [
            'token' => JWTAuth::refresh(),
            'token_time_expired' => time() + 60 * config('jwt.ttl'),
        ]]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        JWTAuth::refresh();
        return response()->json(['message' => 'successfully_logged_out']);
    }

    /**
     * @param $driver
     * @return mixed
     */
    public function redirectTo($driver)
    {
        $this->authHelper->checkAuthDriverExists($driver);
        return Socialite::driver($driver)->stateless()->redirect()->getTargetUrl();
    }

    /**
     * /auth/{driver}/callback
     * @param $driver
     * @param Request $request
     * @return JsonResponse
     * @response {
     *  "need_email": true,
     *  "token": "dsfgdfgdfsgsdfgsfdvrewgwr3",
     *  "token_time_expired": 160000
     *  "user": "(object) user"
     * }
     */
    public function handleCallback($driver, Request $request)
    {

        $this->authHelper->checkAuthDriverExists($driver);

        try {
            /**
             * @var User $socialUser
             */
            $socialUser = Socialite::driver($driver)->stateless()->user();
            $user = $this->userHelper->processSocialUser($socialUser, $driver, $request->get('ref_code'));
        } catch (Exception $exception) {
            return response()->json(['non_field_error' => [$exception->getMessage()]], 400);
        }

        if (!$user) {
            return response()->json(['data' => [
                'need_email' => true,
                'token' => $socialUser->token,
                'token_time_expired' => time() + 60 * config('jwt.ttl'),
            ]]);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(['data' => [
            'need_email' => false,
            'token' => $token,
            'token_time_expired' => time() + 60 * config('jwt.ttl'),
            'user' => (new UserResource($user))->jsonSerialize(),
        ]]);
    }

    /**
     * @param CreateEmailForSocialUserRequest $request
     * @return JsonResponse
     * @response {
     *  "token": "dsfgdfgdfsgsdfgsfdvrewgwr3",
     *  "token_time_expired": 160000
     *  "user": "(object) user"
     * }
     */
    public function createEmailForSocialUser(CreateEmailForSocialUserRequest $request)
    {
        $socialData = SocialUser::where('token', $request->get('token'))->first();

        if (!$socialData) {
            return response()->json(['non_field_error' => [__('auth.failed')]], 400);
        }

        $user = $this->userHelper->createUserFromSocial(
            $request->get('email'),
            $socialData->name,
            $socialData->nickname,
            $socialData->avatar,
            $request->get('password'),
            $request->get('ref_code')
        );
        $socialData->user_id = $user->id;
        $socialData->save();
        $token = JWTAuth::fromUser($user);


        return response()->json(['data' => [
            'token' => $token,
            'token_time_expired' => time() + 60 * config('jwt.ttl'),
            'user' => (new UserResource($user))->jsonSerialize()
        ]]);
    }


}
