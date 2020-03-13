<?php


namespace App\Entities\Chat;


use App\Entities\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'message',
        'user_id',
        'room_id'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
