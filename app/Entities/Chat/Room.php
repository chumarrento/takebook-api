<?php


namespace App\Entities\Chat;


use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['advertiser_id', 'buyer_id'];

    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id', 'id');
    }
}
