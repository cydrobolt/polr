<?php

namespace App\Providers;

use App\Events\LinkWasModified;
use App\Events\LinkWasResolved;
use App\Listeners\ExpireLinkCache;
use App\Listeners\TrackLinkResolution;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        LinkWasResolved::class => [
            TrackLinkResolution::class,
        ],
        LinkWasModified::class => [
            ExpireLinkCache::class
        ]
    ];
}
