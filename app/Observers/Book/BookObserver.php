<?php


namespace App\Observers\Book;

use App\Entities\Auth\User;
use App\Entities\Book\Book;
use App\Entities\Notification;
use App\Mail\NotifyAdminMail;
use Illuminate\Support\Facades\Mail;

class BookObserver
{
    public function created(Book $book)
    {
		Notification::create([
			'reason' => 'BOOK_CREATED',
			'book_id' => $book->id,
			'user_id' => $book->user_id
		]);
//       foreach ($users as $user) {
//           Mail::to($user)->send(new NotifyAdminMail(['user' => $user, 'book' => $book]));
//       }
    }

    public function updated(Book $book)
	{

	}

}
