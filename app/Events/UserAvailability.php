<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Broadcasting\Channel;
// use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// use Illuminate\Foundation\Events\Dispatchable;
use App\User;
class UserAvailability implements ShouldBroadcast
{
    use SerializesModels;
    

    public $user;
    public $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $status)
    {
        $this->user = $user->people;
        $this->status = $status;
        
    }
    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {   

        return ['user-availability'];
        // return new Channel('userAvailability');
    }

}