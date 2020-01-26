<?php


namespace App\Observers;

use App\Entities\Notification;
use App\Entities\SWClient;
use App\Services\NotificationService;

class NotificationObserver
{

	public function created(Notification $notification)
	{
		$notificationService = new NotificationService();
		$userServiceWorkerClients = SWClient::where('user_id', '=', $notification->user_id)->get();
		foreach ($userServiceWorkerClients as $serviceWorkerClient) {
			$notificationService->sendWebPushNotification($serviceWorkerClient, $notification);
		}
	}

}
