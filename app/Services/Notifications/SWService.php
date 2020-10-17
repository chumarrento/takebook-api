<?php

namespace App\Services\Notifications;

use App\Entities\SWClient;
use App\Entities\User\User;
use Illuminate\Support\Facades\Log;
use LaravelFCM\Message\OptionsBuilder;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class SWService implements SendGateway
{
	protected $auth;
	protected $webPush;

	public function __construct()
	{
		$this->auth = config('serviceworker');

		$this->webPush = new WebPush($this->auth);
	}

	public function send(User $user, array $payload): void
	{
		$serviceClients = $user->getSWClientsAttribute();
		$serviceClients->each(function ($serviceClient) use ($payload) {
			$subscription = $this->createSubscription($serviceClient);
			$this->webPush->sendNotification($subscription, json_encode($payload));
		});
	}

	private function createSubscription(SWClient $serviceWorkerClient): Subscription
	{
		return Subscription::create([
			'endpoint' => $serviceWorkerClient->endpoint,
			'keys' => [
				'p256dh' => $serviceWorkerClient->key_p256dh,
				'auth' => $serviceWorkerClient->key_auth
			],
		]);
	}
}
