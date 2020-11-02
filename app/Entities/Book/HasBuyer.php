<?php


namespace App\Entities\Book;


use App\Entities\User\User;
use Illuminate\Database\Eloquent\Model;

class HasBuyer extends Model
{
	protected $table = 'book_has_buyer';

	protected $fillable = [
		'book_id',
		'buyer_id',
		'accepted',
		'answered_at'
	];

	public function book()
	{
		return $this->belongsTo(Book::class, 'book_id', 'id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'buyer_id', 'id');
	}
}
