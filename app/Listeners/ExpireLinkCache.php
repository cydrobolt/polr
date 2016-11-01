<?php

namespace App\Listeners;

use App\Events\LinkWasResolved;
use Illuminate\Support\Facades\Cache;

class ExpireLinkCache
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
        Cache::forget("{$event->link->short_url}/{$event->link->secret_key}");
    }
}