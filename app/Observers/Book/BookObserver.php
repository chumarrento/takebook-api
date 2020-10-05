<?php


namespace App\Observers\Book;

use App\Entities\User\User;
use App\Entities\Book\Book;
use App\Entities\Notification;
use App\Events\BookAccepted;
use App\Mail\NotifyAdminMail;
use Illuminate\Support\Facades\Mail;

class BookObserver
{
	public function created(Book $book)
	{
//       foreach ($users as $user) {
//           Mail::to($user)->send(new NotifyAdminMail(['user' => $user, 'book' => $book]));
//       }
	}

	public function updated(Book $book)
	{
		if ($book->status_id == 2) {
			Notification::create([
				'reason' => 'BOOK_ACCEPTED',
				'book_id' => $book->id,
				'user_id' => $book->user_id
			]);
			event(new BookAccepted($book));
		}
	}

}
