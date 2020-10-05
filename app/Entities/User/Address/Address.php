<?php


namespace App\Entities\User\Address;


use App\Entities\User\User;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
	protected $fillable = [
		'street',
		'number',
		'neighborhood',
		'city',
		'state',
		'zip_code',
		'latitude',
		'longitude',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
}
