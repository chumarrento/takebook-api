<?php


namespace App\Observers;

use App\Entities\Book\Book;
use App\Entities\FCMClient;
use App\Entities\Notification;
use App\Entities\SWClient;
use App\Events\NewNotification;
use App\Services\NotificationService;

class NotificationObserver
{

	public function created(Notification $notification)
	{
		$notificationService = new NotificationService();
		$userServiceWorkerClients = SWClient::where('user_id', '=', $notification->user_id)->get();
		$userFCMTokens = FCMClient::where('user_id', '=', $notification->user_id)->get();
		$book = Book::find($notification->book_id);
		$payload = [
			'id' => $notification->id,
			'reason' => $notification->reason,
			'book' => $book,
			'created_at' => $notification->created_at
		];
		event(new NewNotification($payload, 'userID'.$notification->user_id));
		foreach ($userServiceWorkerClients as $serviceWorkerClient) {
			$notificationService->sendWebPushNotification($serviceWorkerClient, $payload);
		}
		foreach ($userFCMTokens as $userFCMToken) {
			$notificationService->sendNotificationToDevice($userFCMToken->token, $payload);
		}
	}

}
