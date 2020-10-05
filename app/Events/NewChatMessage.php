<?php

namespace App\Events;

use App\Entities\Chat\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewChatMessage implements ShouldBroadcast
{
	use InteractsWithSockets, SerializesModels, Queueable;

	public $message;
	public $room_id;

	private $buyerChannel;
	private $advertiserChannel;

	public function __construct(Message $message)
	{
		$this->message = $message;
		$this->room_id = $message->room_id;

		$room = $message->room;

		$this->buyerChannel = "userID{$room->buyer_id}";
		$this->advertiserChannel = "userID{$room->advertiser_id}";
	}

	public function broadcastOn()
	{
		return [$this->advertiserChannel, $this->buyerChannel];
	}

	public function broadcastAs()
	{
		return 'new-message';
	}
}
