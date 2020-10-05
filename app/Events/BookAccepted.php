<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BookAccepted implements ShouldBroadcast
{
  use InteractsWithSockets, SerializesModels, Queueable;

  public $message;

  public function __construct($message)
  {
      $this->message = $message;
  }

  public function broadcastOn()
  {
      return ['all-clients'];
  }

  public function broadcastAs()
  {
      return 'book-accepted';
  }
}
