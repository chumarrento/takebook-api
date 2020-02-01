<?php


namespace App\Services;

use App\Entities\Book\Book;
use App\Entities\Notification;
use App\Entities\SWClient;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class NotificationService
{
	protected $auth;
	protected $webPush;

	public function __construct()
	{
		$this->auth = [
			'VAPID' => [
				'subject' => 'mailto:felipebarros.dev@gmail.com',
				'publicKey' => env('PUBLIC_KEY'),
				'privateKey' => env('PRIVATE_KEY'),
			],
		];
		try {
			$this->webPush = new WebPush($this->auth);
		} catch (\ErrorException $e) {
		}
	}

	public function createNotification($book)
	{
		Notification::create([
			'reason' => 'BOOK_CREATED',
			'book_id' => $book->id,
			'user_id' => $book->user_id
		]);
	}

	public function sendWebPushNotification(SWClient $serviceWorkerClient, $notification)
	{
		$subscription = Subscription::create([
			'endpoint' => $serviceWorkerClient->endpoint,
			'keys' => [
				'p256dh' => $serviceWorkerClient->key_p256dh,
				'auth' => $serviceWorkerClient->key_auth
			],
		]);

		try {
			$this->webPush->sendNotification($subscription, $notification);
			/** @var MessageSentReport $report */
			foreach ($this->webPush->flush() as $report) {
				$endpoint = $report->getEndpoint();
				if ($report->isSuccess()) {
					Log::info("[v] Message sent successfully for subscription {$endpoint}.");
				} else {
					Log::info("[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
					/** @var \Psr\Http\Message\RequestInterface $requestToPushService */
					$requestToPushService = $report->getRequest();

					/** @var \Psr\Http\Message\ResponseInterface $responseOfPushService */
					$responseOfPushService = $report->getResponse();

					/** @var string $failReason */
					$failReason = $report->getReason();

					/** @var bool $isTheEndpointWrongOrExpired */
					$isTheEndpointWrongOrExpired = $report->isSubscriptionExpired();
				}
			}
		} catch (\ErrorException $e) {
		}
	}
}
