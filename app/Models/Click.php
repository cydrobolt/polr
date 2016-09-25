<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    /**
     * @var string
     */
    protected $table = 'clicks';

    /**
     * @var array
     */
    protected $fillable = ['link_id', 'ip', 'country', 'referer', 'referer_host', 'user_agent'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id');
    }

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->date))
            {
                $model->date = $model->freshTimestamp();
            }
        });
    }
}