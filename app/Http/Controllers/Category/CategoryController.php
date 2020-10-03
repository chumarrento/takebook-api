<?php


namespace App\Http\Controllers\Category;


use App\Entities\Category\Category;
use App\FieldManagers\Category\CategoryFieldManager;
use App\Http\Controllers\ApiController;
use App\Repositories\Category\CategoryRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends ApiController
{
    use ApiResponse;

    public function __construct(Category $model, CategoryRepository $repository, CategoryFieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
        $this->model = $model;
        $this->repository = $repository;
        $this->middleware('auth:api', ['except' => ['getCategories', 'getCategory']]);
        $this->middleware( 'admin', ['except' => ['getCategories', 'getCategory']]);
    }

    /**
     * @OA\Get(
     *     path="/categories",
     *     summary="Lista todas as categorias",
     *     operationId="GetCategories",
     *     tags={"categories"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome da categoria",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="includes",
     *         in="query",
     *         description="Faz o include das relações ",
     *         required=false,
     *         @OA\Schema(
     *           type="array",
     *           @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getCategories(Request $request)
    {
        return parent::index($request); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     summary="Cria uma nova categoria",
     *     operationId="PostCategories",
     *     tags={"categories"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome da categoria",
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
    public function postCategory(Request $request)
    {
        return parent::store($request); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     summary="Lista uma categoria",
     *     operationId="GetCategory",
     *     tags={"categories"},
    *      security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da categoria",
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
    public function getCategory(Request $request, int $id)
    {
        return parent::show($request, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     summary="Atualiza uma categoria",
     *     operationId="PutCategories",
     *     tags={"categories"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da categoria",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome da categoria",
     *         required=false,
     *         @OA\Schema(
     *           type="number"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function putCategory(Request $request, int $id)
    {
        return parent::update($request, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     summary="Apaga uma categoria",
     *     operationId="DeleteCategory",
     *     tags={"categories"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da categoria",
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
    public function deleteCategory(int $id)
    {
        return parent::destroy($id); // TODO: Change the autogenerated stub
    }
}
