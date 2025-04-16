<?php

namespace App\Http\Helpers;

use App\Models\SocialType;
use App\Models\SocialUser;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use TCG\Voyager\Models\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthHelper
{
    /**
     * @param Model $user
     * @return array
     * @throws Exception
     */
    public function fromUser($user)
    {
        try {
            if (!$token = JWTAuth::fromUser($user)) {
                throw new Exception(__('auth.failed'));
            }
        } catch (JWTException $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());

        }

        $createdAt = new DateTime($user->created_at);

        return [
            'token' => $token,
            'token_time_expired' => time() + 60 * config('jwt.ttl'),
            'user' => [
                'name' => $user->name,
                'created_at' => $createdAt->format('d.m.Y'),
                'avatar' => empty($user->avatar) ? '' : url('storage/' . $user->avatar),
                'description' => empty($user->description) ? '' : $user->description,
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'phone' => $user->phone,
                'email' => $user->email,
            ],
        ];
    }

    /**
     * @param Model $user
     * @param string $loginType
     * @throws Exception
     */
    public function userCheck(Model $user, string $loginType)
    {
        if (!empty($user->blocked))
            throw new Exception(__('auth.The user blocked'));

        if ((int)$user->is_full_registration != 1)
            throw new Exception(__('auth.Registration is not completely finished'));

        if (empty($user->email_verified_at) && $loginType == 'email')
            throw new Exception(__('auth.The user email address is not verified'));

        if (empty($user->phone_verified_at) && $loginType == 'phone')
            throw new Exception(__('auth.The user phone is not verified'));
    }

    public function getUser()
    {
        $user = null;

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {

        }

        return $user;
    }


    /**
     * @param $driver
     * @return void
     * @throws HttpResponseException
     */
    public function checkAuthDriverExists($driver)
    {
        if (!SocialType::where('key', $driver)->exists()) {
            throw new HttpResponseException(response()->json(['non_field_error' => [__('auth.This driver not exists')]])->setStatusCode(404));
        }
    }





}
