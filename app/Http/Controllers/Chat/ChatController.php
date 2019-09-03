<?php


namespace App\Http\Controllers\Chat;


use App\Entities\Auth\User;
use App\Entities\Chat\Message;
use App\Entities\Chat\Room;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    use ApiResponse;

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

        return $this->success($rooms);
    }

    /**
     * @OA\Get(
     *     path="/rooms/{room_id}/messages",
     *     summary="Lista todas as mensagens de um chat",
     *     operationId="GetMessages",
     *     tags={"chat"},
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
     *     summary="Cria uma mensagem em um chat",
     *     operationId="PostMessage",
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
    public function storeMessage(Request $request, int $advertiserId, int $buyerId)
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
}
