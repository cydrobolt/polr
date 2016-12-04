<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';

    public function hasAccess($providedSecretKey)
    {
        if (empty($this->secret_key)) {
            return true;
        }
        if ($this->secret_key === $providedSecretKey) {
            return true;
        }
        return false;
    }
}
