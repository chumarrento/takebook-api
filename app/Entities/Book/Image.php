<?php


namespace App\Entities\Book;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'book_images';

    protected $fillable = ['cover', 'book_id'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
