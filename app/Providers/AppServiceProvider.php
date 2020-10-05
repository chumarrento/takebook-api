<?php

namespace App\Providers;

use App\Entities\Book\Book;
use App\Entities\Chat\Message;
use App\Entities\Chat\Room;
use App\Entities\Notification;
use App\Observers\Book\BookObserver;
use App\Observers\Chat\MessageObserver;
use App\Observers\Chat\RoomObserver;
use App\Observers\NotificationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Book::observe(BookObserver::class);
        Notification::observe(NotificationObserver::class);
        Message::observe(MessageObserver::class);
        Room::observe(RoomObserver::class);
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
