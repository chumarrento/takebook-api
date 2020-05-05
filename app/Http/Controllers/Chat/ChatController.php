<?php


namespace App\Http\Controllers\Chat;


use App\Entities\User\User;
use App\Entities\Chat\Message;
use App\Entities\Chat\Room;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    use ApiResponse;

    public function __construct()
	{
		$this->middleware('auth:api');
	}

	/**
     * @OA\Get(
     *     path="/rooms",
     *     summary="Lista todos os chats do usuário autenticado",
     *     operationId="GetRooms",
     *     tags={"chat"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getRooms()
    {
        $rooms = Room::where('buyer_id', '=', Auth::user()->getAuthIdentifier())
            ->orWhere('advertiser_id', '=', Auth::user()->getAuthIdentifier())->get();

        $newRooms = [];
        foreach ($rooms as $room) {
        	$userId = $room->buyer_id != Auth::user()->id ? $room->buyer_id : $room->advertiser_id;
        	$user = User::where('id', $userId)->get(['first_name', 'last_name']);

        	$message = Message::where('room_id', $room->id)->orderBy('created_at', 'desc')->first();

        	$newRooms[] = [
        		'room_id' => $room->id,
        		'user' => $user,
				'message' => $message
			];
		}

        return $this->success($newRooms);
    }

    /**
     * @OA\Get(
     *     path="/rooms/{room_id}/messages",
     *     summary="Lista todas as mensagens de um chat",
     *     operationId="GetMessages",
     *     tags={"chat"},
	 *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="room_id",
     *         in="path",
     *         description="ID do chat",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getMessages(int $room_id)
    {
        return $this->success(Message::where('room_id', '=', $room_id)->get());
    }

    /**
     * @OA\Post(
     *     path="/rooms/{advertiserId}/w/{buyerId}",
     *     summary="Cria um chat e uma mensagem em um chat",
     *     operationId="PostChat",
     *     tags={"chat"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="advertiserId",
     *         in="path",
     *         description="ID do anunciante",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="buyerId",
     *         in="path",
     *         description="ID do comprador",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="message",
     *         in="query",
     *         description="Mensagem",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function postChatAndMessage(Request $request, int $advertiserId, int $buyerId)
    {
        $request->merge(['advertiser_id' => $advertiserId, 'buyer_id' => $buyerId]);
        $this->validate($request, [
            'advertiser_id' => 'required|exists:users,id',
            'buyer_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $room = Room::where([
            ['buyer_id', '=', $buyerId],
            ['advertiser_id', '=', $advertiserId]
        ])->first();

        if (!$room) {
            User::find($advertiserId)->rooms()->attach($buyerId);

            $room = Room::where([
                ['buyer_id', '=', $buyerId],
                ['advertiser_id', '=', $advertiserId]
            ])->first();
        }

        $message = $request->all();
        $message['room_id'] = $room->id;
        $message['user_id'] = Auth::user()->getAuthIdentifier();
        return $this->success(Message::create($message));
    }


	/**
	 * @OA\Post(
	 *     path="/rooms/{roomId}/messages",
	 *     summary="Cria uma mensagem em um chat",
	 *     operationId="PostMessage",
	 *     tags={"chat"},
	 *     security={{"apiToken":{}}},
	 *     @OA\Parameter(
	 *         name="roomId",
	 *         in="path",
	 *         description="ID do chat",
	 *         required=true,
	 *         @OA\Schema(
	 *           type="integer"
	 *         )
	 *     ),
	 *     @OA\Parameter(
	 *         name="message",
	 *         in="query",
	 *         description="Mensagem",
	 *         required=true,
	 *         @OA\Schema(
	 *           type="string"
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="...",
	 *     ),
	 *  )
	 */
    public function sendMessage(Request $request, int $roomId)
	{
		$this->validate($request, [
			'message' => 'string'
		]);

		$room = Room::findOrFail($roomId);

		$message = [
			'message' => $request->input('message'),
			'user_id' => Auth::user()->id
		];

		$message = $room->messages()->create($message);

		return $this->created($message);
	}
}
