<?php

namespace App\Listerners;

use App\Events\ReplyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReplyEventListener
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
     * @param  ReplyEvent  $event
     * @return void
     */
    public function handle(ReplyEvent $event)
    {
        $event->question->user()->get()[0]->notifications()->create([
            'body' => 'someone replied to your question',
            'read' => false,
            'question_slug' => $event->question->slug
        ]);
    }
}
