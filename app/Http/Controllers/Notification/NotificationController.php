<?php


namespace App\Http\Controllers\Notification;


use App\Entities\Notification;
use Illuminate\Http\Request;

class NotificationController
{

	public function openNotification(Request $request)
	{
		$notification = Notification::find($request->notification_id);
		if(!$notification) return response()->json(['ok' => 'false', 'error' => 'Notification not found'], 404);
		$notification->opened = true;
		$notification->save();
		return response()->json(['ok' => 'true'], 200);
	}

}
