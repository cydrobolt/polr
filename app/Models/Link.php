<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Link extends Model {
    protected $table = 'links';

    public function setLongUrlAttribute($long_url) {
        // Set crc32 hash and long_url
        // whenever long_url is set on a Link instance

        // Generate 32-bit unsigned integer crc32 value
        // Use sprintf to prevent compatibility issues with 32-bit systems
        // http://php.net/manual/en/function.crc32.php
        $crc32_hash = sprintf('%u', crc32($long_url));

        $this->attributes['long_url'] = $long_url;
        $this->attributes['long_url_hash'] = $crc32_hash;
    }

    public function scopeLongUrl($query, $long_url) {
        // Allow quick lookups with Link::longUrl that make use
        // of the indexed crc32 hash to quickly fetch link
        $crc32_hash = sprintf('%u', crc32($long_url));

        return $query
            ->where('long_url_hash', $crc32_hash)
            ->where('long_url', $long_url);
    }
}
