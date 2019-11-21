<?php


namespace App\Entities\Book;


use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'book_status';

    protected $fillable = ['name'];

    public function books()
    {
        return $this->hasMany(Book::class, 'status_id', 'id');
    }
}
