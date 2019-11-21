<?php


namespace App\Entities\Book;


use App\Entities\Auth\User;
use App\Entities\Category\Category;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'description',
        'price',
        'approved_at',
        'condition_id',
        'status_id',
        'user_id'
    ];

    protected $appends = ['count_likes', 'user', 'covers_url'];

    public function categories()
    {
        return $this->belongsToMany(Category::class,
            'book_categories',
            'book_id',
            'category_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class,
            'user_like_books',
            'book_id',
            'user_id')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'book_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }

    public function conditions()
    {
        return $this->belongsTo(Condition::class, 'condition_id', 'id');
    }

    public function getCountLikesAttribute()
    {
        return $this->likes()->count();
    }

    public function getUserAttribute()
    {
        return $this->user()->getResults();
    }

    public function getCoversUrlAttribute()
    {
        $data = [];
        foreach ($this->images()->getResults() as $image) {
            $url = env('APP_URL') .'/storage/' . $image->cover;
            $data[] = ['url' => $url, 'image_id' => $image->id];
        }

        return $data;
    }
}
