<?php


namespace App\Http\Controllers\Chat;


use App\Entities\User\User;
use App\Entities\Chat\Message;
use App\Entities\Chat\Room;
use App\Events\NewChatRoom;
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
        	$user = User::where('id', $userId)->first(['id', 'first_name', 'last_name'])
				->makeHidden(['address', 'total_sales']);

        	$messages = Message::where('room_id', $room->id)->orderBy('created_at', 'desc')
				->limit(15)
				->get();

        	$newRooms[] = [
        		'room_id' => $room->id,
        		'user' => $user,
				'messages' => $messages
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
     *     path="/rooms",
     *     summary="Cria um chat e uma mensagem em um chat",
     *     operationId="PostChat",
     *     tags={"chat"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="target_id",
     *         in="query",
     *         description="ID do usuário ",
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
    public function postChatAndMessage(Request $request)
    {
        $this->validate($request, [
            'target_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);
        $user = Auth::user();
		$targetUser = User::find($request->input('target_id'));
        $room = $user->hasRoomWith($targetUser);


        if (!$room) {
			$user->rooms()->attach($targetUser);

			$room = $user->hasRoomWith($targetUser);
        }
        $message = $request->all();
        $message['room_id'] = $room->id;
        $message['user_id'] = $user->id;
        $message = Message::create($message);
		$room = $user->hasRoomWith($targetUser);

		event(new NewChatRoom($room));

		return $this->created($message);
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
