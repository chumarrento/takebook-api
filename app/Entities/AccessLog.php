<?php

namespace App\Entities;

use App\Entities\User\User;
use Illuminate\Database\Eloquent\Model;


class AccessLog extends Model
{
    protected $fillable = ["user_id", "ip", "type"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
