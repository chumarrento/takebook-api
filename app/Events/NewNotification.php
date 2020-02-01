<?php

namespace App\Events;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewNotification implements ShouldBroadcast
{
	use InteractsWithSockets, SerializesModels;

	public $message;
	protected $channel;

	public function __construct($message, $channel)
	{
		$this->message = $message;
		$this->channel = $channel;
	}

	public function broadcastOn()
	{
		return [$this->channel];
	}

	public function broadcastAs()
	{
		return 'new-notification';
	}
}
