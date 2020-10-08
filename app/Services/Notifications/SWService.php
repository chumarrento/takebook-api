<?php

namespace App\Services\Notifications;

use App\Entities\User\User;

class SWService implements SendGateway
{
	public function send(User $user, array $payload): void
	{
		/**
		 * PEGAR INFO NECESSARIA PRA ENVIAR A MENSAGEM
		 */
	//		$user->getSWClientsAttribute();
	}
}
