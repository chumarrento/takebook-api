<?php


namespace App\Services;

use App\Entities\SWClient;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class NotificationService
{
	protected $auth;
	protected $webPush;
	protected $optionBuilder;

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
			$this->optionBuilder = new OptionsBuilder();
			$this->optionBuilder->setTimeToLive(60 * 20);
		} catch (\ErrorException $e) {
		}
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

	public function sendNotificationToDevice(string $token, $payload)
	{
		switch ($payload['reason']) {
			case 'BOOK_ACCEPTED':
				$title = 'Livro aceito!';
				$body = 'Seu livro foi aceito e cadastrado no nosso banco de dados!';
				break;
			case 'BOOK_CREATED':
				$title = 'Livro enviado para análise!';
				$body = 'Um novo livro foi enviado para análise.';
				break;
			default:
				$title = 'TESTE';
				$body = 'Sei la';
				break;
		}
		$notificationBuilder = new PayloadNotificationBuilder($title);
		$notificationBuilder->setBody($body)->setSound('default');

		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData(['book' => $payload['book']]);

		$option = $this->optionBuilder->build();
		$notification = $notificationBuilder->build();
		$data = $dataBuilder->build();

		//TODO tratar o retorno do fireloiros
		$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
	}
}
