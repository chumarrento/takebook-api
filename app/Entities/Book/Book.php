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
        'status',
        'user_id'
    ];

    protected $appends = ['count_likes', 'user'];

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

    public function getCountLikesAttribute()
    {
        return $this->likes()->count();
    }

    public function getUserAttribute()
    {
        return $this->user()->getResults();
    }
}
