<?php


namespace App\Http\Controllers\Notification;
use App\Entities\SWClient;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceWorkerController extends Controller
{
	use ApiResponse;

	public function __construct()
	{
		$this->middleware('auth');
		$this->model = Auth::user();
	}

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

		return $this->success(['ok' => 'true', 'swclient' => $swclient]);
	}
	public function unsubscribeClient(Request $request)
	{
		$swclient = SWClient::find($request->id);
		if(!$swclient) return $this->notFound(['ok' => 'false', 'error' => 'ServiceWorkerClient not found']);
		$swclient->delete();
		return $this->success(['ok' => $swclient ? 'false' : 'true']);
	}

	public function receiveClientToken(Request $request)
	{
		if ($request->has('token')) {
			$userTokens = $this->model->fcm()->getResults();
			foreach($userTokens as $userToken) {
				if(hash_equals($userToken->token, $request->input('token'))) {
					return $this->noContent();
				}
			}
			return $this->success($this->model->fcm()->create($request->all()));
		}
		return $this->noContent();
	}
}
