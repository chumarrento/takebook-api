<?php

namespace App\Listeners;

use App\Events\NewChatMessage;

class MessageChatListener
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
	 * @param  NewChatMessage  $event
	 * @return void
	 */
	public function handle(NewChatMessage $event)
	{
		//
	}
}
