<?php

namespace App\Services\Notifications;

use App\Entities\User\User;

interface SendGateway
{
	/**
	 * @param User $user
	 * @param array $payload
	 * @return void
	 */
	public function send(User $user, array $payload): void;
}
