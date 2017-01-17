<?php

namespace App\Listeners;

use App\Events\ConversationFlaggedEvent;

class ConversationFlaggedEventListener
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
     * @param  ConversationFlaggedEvent  $event
     * @return void
     */
    public function handle(ConversationFlaggedEvent $event)
    {
        $conversation = $event->conversation;

        $conversation->flagged()->create([
            'website_id' => $conversation->website->id,
            'user_id' => auth()->user()->id,
            'notes' => $conversation->notes,
        ]);
    }
}
