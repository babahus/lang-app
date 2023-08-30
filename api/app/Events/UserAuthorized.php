<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAuthorized
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $roleName;
    public $token;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $roleName, $token)
    {
        $this->user = $user;
        $this->roleName = $roleName;
        $this->token = $token;
    }
}
