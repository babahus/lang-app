<?php

namespace App\Listeners;

use App\Events\UserAuthorized;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class CacheUserRolesAndToken
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserAuthorized $event)
    {
        $user = $event->user;
        $roleName = $event->roleName;

        $dataUserRole = [
            'role_id' => $user->roles->where('name', $roleName)->first()->id,
            'user_id' => $user->id
        ];

        $dataUserToken = [
            'token' => $event->token,
            'user_id' => $user->id
        ];

        Cache::put("users_role_" . $user->id, $dataUserRole, now()->addHours(intval(env('CACHE_DURATION_HOURS'))));
        Cache::put("users_token_" . $user->id, $dataUserToken, now()->addHours(intval(env('CACHE_DURATION_HOURS'))));
    }
}
