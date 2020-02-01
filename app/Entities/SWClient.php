<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SWClient extends Model
{
	protected $table = "swclients";

	protected $fillable = [
		'endpoint', 'expiration_time', 'key_p256dh', 'key_auth', 'user_id', 'user_token'
	];
}
