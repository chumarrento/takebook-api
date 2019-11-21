<?php


namespace App\Http\Controllers\Report;



use App\Entities\Report\Status;
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
     *     path="/reports/status",
     *     summary="Lista todos os status de denúncias",
     *     operationId="GetStatusReports",
     *     tags={"reports"},
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
