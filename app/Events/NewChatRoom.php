<?php


namespace App\Events;


use App\Entities\Chat\Room;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewChatRoom implements ShouldBroadcast
{
	use InteractsWithSockets, SerializesModels, Queueable;

	public $room;

	private $advertiserChannel;
	private $buyerChannel;

	public function __construct(Room $room)
	{
		$this->room = $room;

		$this->advertiserChannel = $room->advertiser_id;
		$this->buyerChannel = $room->buyer_id;
	}

	public function broadcastOn()
	{
		return [$this->advertiserChannel, $this->buyerChannel];
	}

	public function broadcastAs()
	{
		return 'new-room';
	}
}
