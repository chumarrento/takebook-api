<?php


namespace App\Http\Controllers\Book;



use App\Entities\Book\Condition;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class ConditionController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/books/conditions",
     *     summary="Lista todos os estados de livros",
     *     operationId="GetConditionsBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getConditons()
    {
        return $this->success(Condition::all());
    }
}
