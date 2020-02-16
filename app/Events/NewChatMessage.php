<?php

namespace App\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewChatMessage implements ShouldBroadcast
{
	use InteractsWithSockets, SerializesModels, Queueable;

	public $message;
	public $room_id;

	public function __construct($message, $room_id)
	{
		$this->message = $message;
		$this->room_id = $room_id;
	}

	public function broadcastOn()
	{
		return ['room'.$this->room_id];
	}

	public function broadcastAs()
	{
		return 'new-message';
	}
}
