<?php


namespace App\Observers;

use App\Entities\Book\Book;
use App\Entities\Notification;
use App\Entities\User\User;
use App\Events\NewNotification;
use App\Jobs\NotifyUserJob;
use App\Services\Notifications\FCMService;
use App\Services\Notifications\SWService;

class NotificationObserver
{
	public function created(Notification $notification)
	{

		$user = User::find($notification->user_id);
		$book = Book::find($notification->book_id);

		// Deveria ter gateway para email também?
		$gateways = collect([
			SWService::class,
			FCMService::class,
		]);

		$payload = [
			'id' => $notification->id,
			'reason' => $notification->reason,
			'book' => $book,
			'created_at' => $notification->created_at
		];

		$gateways->each(function ($gateway) use ($payload, $user) {
			dispatch(new NotifyUserJob(new $gateway, $user, $payload));
		});

		// TODO: Criar gateway para notificar via WebSocket.
		event(new NewNotification($payload, 'userID'.$notification->user_id));

//				$notificationService = new NotificationService();
		//		$userServiceWorkerClients = $user->SWClients;
		//		$userFCMTokens = $user->FCMClients;

		//		foreach ($userServiceWorkerClients as $serviceWorkerClient) {
//					$notificationService->sendWebPushNotification($serviceWorkerClient, $payload);
		//		}
		//
		//		foreach ($userFCMTokens as $userFCMToken) {
		//			$notificationService->sendNotificationToDevice($userFCMToken->token, $payload);
		//		}
	}

}
