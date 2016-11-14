<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model {

    /**
     * @var string
     */
    protected $table = 'links';

    /**
     * @return HasMany
     */
    public function clicks()
    {
        return $this->hasMany(Click::class);
    }
}
