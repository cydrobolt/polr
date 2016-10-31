<?php

namespace App\Events;

use App\Models\Link;
use Illuminate\Queue\SerializesModels;

class LinkWasModified extends Event
{
    use SerializesModels;

    public $link;

    /**
     * Create a new event instance.
     * @param Link $link
     */
    public function __construct(Link $link)
    {
        $this->link = $link;
    }

}
