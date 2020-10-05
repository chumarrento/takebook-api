<?php


namespace App\Http\Controllers\Notification;


use App\Entities\Notification;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
	use ApiResponse;

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function openNotification(Request $request, int $notificationId)
	{
		$notification = Notification::findOrFail($notificationId);

		if ($notification->user_id !== Auth::user()->getAuthIdentifier()) return $this->unauthorized();

		$notification->opened = true;
		$notification->save();
		return $this->success(['ok' => 'true']);
	}

}
