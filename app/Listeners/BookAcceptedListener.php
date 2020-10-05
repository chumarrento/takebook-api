<?php

namespace App\Listeners;

use App\Events\BookAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookAcceptedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BookAccepted  $event
     * @return void
     */
    public function handle(BookAccepted $event)
    {
        //
    }
}
