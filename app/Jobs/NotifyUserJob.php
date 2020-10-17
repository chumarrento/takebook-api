<?php

namespace App\Jobs;

use App\Entities\User\User;
use App\Services\Notifications\SendGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserJob extends Job implements ShouldQueue
{
	use SerializesModels, InteractsWithQueue, Queueable;
	/**
	 * @var User
	 */
	public $user;

	/**
	 * @var SendGateway
	 */
	public $gateway;

	/**
	 * @var array
	 */
	public $payload;

	/**
	 * NotifyUserJob constructor.
	 * @param SendGateway $gateway
	 * @param User $user
	 * @param array $payload
	 */
	public function __construct($gateway, User $user, array $payload)
	{
		$this->gateway = $gateway;
		$this->user = $user;
		$this->payload = $payload;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{

		//TODO achar o erro do gateway
//		try {
//			$this->gateway->send($this->user, $this->payload);
//		} catch (\Exception $exception) {
//			$this->failed($exception);
//		}
	}

	public function failed(\Exception $exception)
	{
		// TODO: Criar tabela de log.
	}
}
