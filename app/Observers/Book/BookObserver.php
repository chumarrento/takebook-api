<?php


namespace App\Observers\Book;


use App\Entities\Auth\User;
use App\Entities\Book\Book;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class BookObserver
{
    public function created(Book $book)
    {
        $users = User::where('is_admin', '=', true)->get();

//        foreach ($users as $user) {
//            Mail::to($user)->send(new ResetPasswordMail(['user' => $user, 'token' => $book]));
//        }
    }

//    public function updated(Book $book)
//    {
//        dd($book);
//        $user = $book->user()->first();
//
//        Mail::to($user)->send(new ResetPasswordMail(['user' => $user, 'token' => $book]));
//    }
}
