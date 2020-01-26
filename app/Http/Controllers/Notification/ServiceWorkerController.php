<?php


namespace App\Http\Controllers\Notification;
use App\Entities\SWClient;
use Illuminate\Http\Request;

class ServiceWorkerController
{
	public function subscribeClient(Request $request)
	{
		$swclient = SWClient::updateOrCreate([
			'user_id' => $request->user_id,
			'endpoint' => $request->endpoint,
			'key_auth' => $request->keys['auth'],
		], [
			'endpoint' => $request->endpoint,
			'expiration_time' => $request->expirationTime,
			'key_p256dh' => $request->keys['p256dh'],
			'user_token' => $request->user_token
		]);

		return response()->json(['ok' => 'true', 'swclient' => $swclient], 200);
	}
	public function unsubscribeClient(Request $request)
	{
		$swclient = SWClient::find($request->id);
		if(!$swclient) return response()->json(['ok' => 'false', 'error' => 'ServiceWorkerClient not found'], 404);
		$swclient->delete();
		return response()->json(['ok' => $swclient ? 'false' : 'true'], 200);
	}
}
