<?php

namespace App\Events;

use App\Events\Event;
use App\Tenant\Conversation;
use App\Website;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UserTakeChatEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $website;

    public $conversation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Website $website, Conversation $conversation)
    {
        $this->website = $website;

        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['take-chat'];
    }
}
