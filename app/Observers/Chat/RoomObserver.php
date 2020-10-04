<?php


namespace App\Observers\Chat;


use App\Entities\Chat\Room;
use App\Events\NewChatRoom;

class RoomObserver
{
	public function created(Room $room)
	{
		event(new NewChatRoom($room));
	}
}
