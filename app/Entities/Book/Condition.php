<?php


namespace App\Entities\Book;


use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $table = 'book_conditions';

    protected $fillable = ['name'];

    public function book() {
        return $this->hasMany(Book::class, 'condition_id', 'id');
    }
}
