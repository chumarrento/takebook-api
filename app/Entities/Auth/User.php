<?php

namespace App\Entities\Auth;

use App\Entities\AccessLog;
use App\Entities\Book\Book;
use App\Entities\Chat\Message;
use App\Entities\Chat\Room;
use App\Entities\Notification;
use App\Entities\Report\Report;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'avatar',
        'email',
        'password',
        'address_street',
        'address_number',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zip_code',
        'is_admin'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'is_admin',
        'avatar'
    ];
    protected $with = ['likes'];
    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        if (empty($this->avatar)) {
            return null;
        }
        return env('APP_URL') .'/storage/' . $this->avatar;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Make a new Password Reset Hash
     *
     * @return string
     */
    public function generateResetToken()
    {
        $token = rand(100000, 999999);
        DB::table('password_resets')->insert(
            [
                'email' => $this->email,
                'token' => $token,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        return $token;
    }

    public function logs()
    {
        return $this->hasMany(AccessLog::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'user_id', 'id');
    }

    public function rooms()
    {
        return $this->belongsToMany(User::class,
            Room::class,
            'advertiser_id',
            'buyer_id')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }

    public function reports()
    {
        return $this->belongsToMany(
            User::class,
            Report::class,
            'denunciator_id',
            'reported_id'
        )
            ->withPivot(['type_id', 'description'])
            ->withTimestamps();
    }

    public function reporteds()
    {
        return $this->belongsToMany(
            User::class,
            Report::class,
            'reported_id',
            'denunciator_id'
        )
            ->withPivot(['type_id', 'description'])
            ->withTimestamps();
    }

    public function likes()
    {
        return $this->belongsToMany(Book::class,
            'user_like_books',
            'user_id',
            'book_id')->withTimestamps();
    }

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

}
