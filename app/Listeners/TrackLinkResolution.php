<?php

namespace App\Listeners;

use App\Events\LinkWasResolved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrackLinkResolution implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  LinkWasResolved  $event
     * @return void
     */
    public function handle(LinkWasResolved $event)
    {
        // ToDo: Here is where all the tracking logic will happen when
        // https://github.com/cydrobolt/polr/pull/231 is merged
        $event->link->clicks++;
        $event->link->save();
    }
}