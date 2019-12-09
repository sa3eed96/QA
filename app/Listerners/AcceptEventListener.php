<?php

namespace App\Listerners;

use App\Events\AcceptEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AcceptEventListener
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
     * @param  AcceptEvent  $event
     * @return void
     */
    public function handle(AcceptEvent $event)
    {
        $event->answer->user()->get()[0]->notifications()->create([
            'body'=> 'Your Answer was accepted',
            'question_slug' => $event->answer->question()->get()[0]->slug,
            'read' => false
        ]);   
    }
}
