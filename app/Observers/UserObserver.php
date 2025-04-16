<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\ViewContactUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        do {
            $token = Str::random(User::$lengthOfRefCode);
        } while (User::where('ref_code', $token)->first());
        $user->ref_code = $token;
        $user->saveQuietly();

        UserSetting::create([
            'user_id' => $user->id,
            'is_hide_user' => false,
            'is_hide_company' => false,
            'is_receive_news' => false,
            'is_receive_messages_by_email' => true,
            'is_receive_comments_by_email' => false,
            'is_receive_price_favorite_by_email' => false,
            'is_receive_messages_by_phone' => false,
            'is_receive_comments_by_phone' => false,
            'is_receive_price_favorite_by_phone' => false,
        ]);

        ViewContactUser::create([
            'user_id' => $user->id,
            'view_contact' => 5,
        ]);

    }

    /**
     * Handle the User "updating" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        $changes = collect($user->getChanges());
        if ($changes->has('email')) {
            $user->email_verified_at = null;
            Log::info("User change email", [$user->toArray(), $changes]);
        }
        if ($changes->has('phone')) {
            $user->phone_verified_at = null;
            Log::info("User change phone", [$user->toArray(), $changes]);
        }
        $user->saveQuietly();
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
