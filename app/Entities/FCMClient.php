<?php


namespace App\Entities;


use App\Entities\Auth\User;
use Illuminate\Database\Eloquent\Model;

class FCMClient extends Model
{
	protected $table = 'fcm_clients';

	protected $fillable = ['token', 'user_id'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
