<?php


namespace App\Entities;
use App\Entities\Auth\User;
use App\Entities\Book\Book;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = [
		'reason', 'opened', 'book_id', 'user_id'
	];

	protected $hidden = ['book_id'];

	public function book()
	{
		return $this->belongsTo(Book::class, 'book_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
