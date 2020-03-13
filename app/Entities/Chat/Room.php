<?php


namespace App\Entities\Chat;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
	use SoftDeletes;

    protected $fillable = ['advertiser_id', 'buyer_id'];

    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id', 'id');
    }
}
