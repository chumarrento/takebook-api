<?php


namespace App\Http\Controllers\Book;

use App\Entities\Book\Status;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class StatusController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/books/status",
     *     summary="Lista todos os status de livros",
     *     operationId="GetStatusBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getStatus()
    {
        return $this->success(Status::all());
    }
}
