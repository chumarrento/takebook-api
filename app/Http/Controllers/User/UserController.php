<?php
/**
 * Created by PhpStorm.
 * User: idani
 * Date: 05/01/2019
 * Time: 16:16
 */

namespace App\Http\Controllers\User;


use App\Entities\Auth\User;
use App\FieldManagers\User\UserFieldManager;
use App\Http\Controllers\ApiController;
use App\Repositories\User\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    use ApiResponse;

    public function __construct(User $model, UserRepository $repository, UserFieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
        $this->model = $model;
        $this->repository = $repository;
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->middleware('admin', ['except' => ['store']]);
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Lista todas os usuários",
     *     operationId="GetUsers",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
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
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="Nome do usuário",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Sobrenome do usuário",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="is_admin",
     *         in="query",
     *         description="Status de Administrador",
     *         required=false,
     *        @OA\Schema(
     *           type="number",
     *           enum={0, 1},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getUsers(Request $request)
    {
        return parent::index($request);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Cria um novo usuário",
     *     operationId="StoreUser",
     *     tags={"users"},
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="Primeiro nome",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Ultimo nome",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_street",
     *         in="query",
     *         description="Rua onde o rapaz mora",
     *         required=false,
     *        @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_number",
     *         in="query",
     *         description="Numero da casa do rapaz",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_city",
     *         in="query",
     *         description="Cidade onde o rapaz mora",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_state",
     *         in="query",
     *         description="Rua onde o rapaz mora",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_zip_code",
     *         in="query",
     *         description="CEP onde o rapaz mora",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail do cidadão",
     *         required=true,
     *        @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Senha do cidadão",
     *         required=true,
     *        @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="is_admin",
     *         in="query",
     *         description="Status de Administrador",
     *         required=true,
     *        @OA\Schema(
     *           type="number",
     *           enum={0, 1},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function postUser(Request $request)
    {
        return parent::store($request);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Atualiza um usuário",
     *     operationId="UpdateUser",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         description="Primeiro nome",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="last_name",
     *         in="query",
     *         description="Ultimo nome",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="address_street",
     *         in="query",
     *         description="Rua onde o rapaz mora",
     *         required=false,
     *        @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_number",
     *         in="query",
     *         description="Numero da casa do rapaz",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_city",
     *         in="query",
     *         description="Cidade onde o rapaz mora",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_state",
     *         in="query",
     *         description="Rua onde o rapaz mora",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="address_zip_code",
     *         in="query",
     *         description="CEP onde o rapaz mora",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail do cidadão",
     *         required=false,
     *        @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Senha do cidadão",
     *         required=false,
     *        @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="is_admin",
     *         in="query",
     *         description="Status de Administrador",
     *         required=false,
     *        @OA\Schema(
     *           type="number",
     *           enum={0, 1},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function putUser(Request $request, int $id)
    {
        return parent::update($request, $id);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Lista um usuário",
     *     operationId="GetUser",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do usuário",
     *         required=true,
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
    public function getUser(Request $request, int $id)
    {
        return parent::show($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Apaga um usuário",
     *     operationId="DeleteUser",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do usuário",
     *         required=true,
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
    public function destroy(int $id)
    {
        return parent::destroy($id); // TODO: Change the autogenerated stub
    }
}
