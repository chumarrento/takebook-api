<?php


namespace App\Observers\Book;


use App\Entities\Auth\User;
use App\Entities\Book\Book;
use App\Mail\NotifyAdminMail;
use Illuminate\Support\Facades\Mail;

class BookObserver
{
    public function created(Book $book)
    {
        $users = User::where('is_admin', '=', true)->get();

//       foreach ($users as $user) {
//           Mail::to($user)->send(new NotifyAdminMail(['user' => $user, 'book' => $book]));
//       }
    }
}
