<?php

namespace App\Services\Notifications;

use App\Entities\User\User;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class FCMService implements SendGateway
{
	protected $optionBuilder;

	public function __construct()
	{
		$this->optionBuilder = new OptionsBuilder();
		$this->optionBuilder->setTimeToLive(60 * 20);
	}

	public function send(User $user, array $payload): void
	{
		$fcmClients = $user->getFCMClientsAttribute();
		$fcmClients->each(function($client) use ($payload) {
			$this->sendNotificationToDevice($client->token, $payload);
		});
	}
	public function sendNotificationToDevice(string $token, $payload)
	{
		$notificationAttributes = $this->getNotificationAttributes($payload['reason']);

		$option = $this->optionBuilder->build();
		$notification = $this->payloadNotificationBuilder($notificationAttributes);
		$data = $this->payloadDataBuilder($payload);

		\FCM::sendTo($token, $option, $notification, $data);
	}

	private function getNotificationAttributes($reason)
	{
		switch ($reason) {
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
		return ['title' => $title, 'body' => $body];
	}

	private function payloadNotificationBuilder($notificationAttributes)
	{
		$notificationBuilder = new PayloadNotificationBuilder($notificationAttributes['title']);
		$notificationBuilder->setBody($notificationAttributes['body'])->setSound('default');
		return $notificationBuilder->build();
	}

	private function payloadDataBuilder($payload)
	{
		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData(['book' => $payload['book'], 'id' => $payload['id']]);
		return $dataBuilder->build();
	}
}
