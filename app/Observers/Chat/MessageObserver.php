<?php


namespace App\Observers\Chat;


use App\Entities\Chat\Message;
use App\Events\NewChatMessage;

class MessageObserver
{
	public function created(Message $message)
	{
	   event(new NewChatMessage($message, $message->room_id));
	}
}
