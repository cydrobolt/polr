<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class User extends Model {
	const ADMIN_ROLE = 'admin';
	const USER_ROLE = 'user';
	
    protected $table = 'users';
}
