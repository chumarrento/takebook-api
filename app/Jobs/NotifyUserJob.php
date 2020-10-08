<?php

namespace App\Jobs;

use App\Entities\User\User;
use App\Services\Notifications\SendGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserJob implements ShouldQueue
{
	use InteractsWithQueue, Queueable, SerializesModels;
	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var SendGateway
	 */
	private $gateway;

	/**
	 * @var array
	 */
	private $payload;

	/**
	 * NotifyUserJob constructor.
	 * @param SendGateway $gateway
	 * @param User $user
	 * @param array $payload
	 */
	public function __construct(SendGateway $gateway, User $user, array $payload)
	{
		$this->gateway = $gateway;
		$this->user = $user;
		$this->payload = $payload;
	}

	public function handle()
	{
		$this->gateway->send($this->user, $this->payload);
	}

	public function failed()
	{
		// TODO: Criar tabela de log.
	}
}
