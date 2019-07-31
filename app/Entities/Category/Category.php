<?php


namespace App\Entities\Category;


use App\Entities\Book\Book;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class,
            'book_categories',
            'category_id',
            'book_id',
            'category_id')->withTimestamps();
    }
}
