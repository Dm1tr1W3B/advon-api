<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use DateInterval;
use DateTime;

class UserPhoneKey extends Model
{
    use HasFactory;

    const USER_VERIFICATION = 'user_verification';
    const RECOVERY = 'password_recovery';

    private $lifeTime = null;

    public function setLifeTimeAttribute(DateInterval $lifeTime)
    {
        $this->lifeTime = $lifeTime;
    }

    public function getLifeTimeAttribute()
    {
        return $this->lifeTime;
    }

    public function setKeyAttribute($key)
    {
        $this->attributes['key'] = Hash::make($key);
    }

    public function compareKey($key)
    {
        return Hash::check($key, $this->key);
    }

    public function getExpiredAtAttribute()
    {
        return $this->updated_at->add($this->lifeTime);
    }

    public function isActual()
    {
        return $this->expired_at > new DateTime('now');
    }

    public function isRequestAvailable(DateInterval $requestInterval)
    {
        $availableAt = $this->updated_at->add($requestInterval);

        if ($availableAt <= new DateTime('now')) {
            return true;
        }

        return $availableAt;
    }
}
