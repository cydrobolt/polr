<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $defaultApiQuota = 5;

    /**
     * Whether API quota is met
     * ToDo: Use laravel's ThrottleRequests
     *
     * @param string|null $anonymousUser
     * @return boolean
     */
    public function hasReachedApiQuota($anonymousUser = null)
    {
        $username = !empty($anonymousUser) ? $anonymousUser : $this->username;
        $apiQuota = !empty($anonymousUser) ? $this->defaultApiQuota : (int)$this->api_quota;
        $last_minute = (new \DateTime())->modify('-1 minute')->format(\DateTime::ISO8601);
        $links_last_minute = Link::where('is_api', 1)
            ->where('creator', $username)
            ->where('created_at', '>=', $last_minute)
            ->count();

        return ($apiQuota > -1 && $links_last_minute >= $apiQuota);
    }
}
