<?php

namespace App\Http\Controllers\User;


use App\Entities\Auth\User;
use App\Entities\Book\Book;
use App\FieldManagers\User\UserFieldManager;
use App\Http\Controllers\ApiController;
use App\Repositories\User\UserRepository;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MeController extends ApiController
{
    use ApiResponse;

    public function __construct(UserRepository $repository, UserFieldManager $fieldManager)
    {
        $this->model = Auth::user();
        $this->fieldManager = $fieldManager;
        $this->repository = $repository;
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/users/me",
     *     summary="Retorna o usuário logado",
     *     operationId="GetAuthUser",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function me()
    {
    	return $this->success($this->model::with('notifications.book')->where(['id' => $this->model->id])->first());
    }

    /**
     * @OA\Put(
     *     path="/users/me",
     *     summary="Atualiza o usuário",
     *     operationId="UpdateAuthUser",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
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
     *         name="address_neighborhood",
     *         in="query",
     *         description="Bairro onde o rapaz mora",
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
     *         description="CEP do cidadão",
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
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function putMe(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'string',
            'last_name' => 'string',
            'email' => 'email',
        ]);

        return parent::update($request, $this->model->getAuthIdentifier());
    }

    /**
     * @OA\Post(
     *     path="/users/me/avatar",
     *     summary="Atualiza o avatar do usuário logado",
     *     operationId="UpdateUserAvatar",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="avatar_file",
     *                     type="file"
     *                 ),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function updateAvatar(Request $request)
    {
        $file = $request->file('avatar_file');
        $filename = "avatars/" . Str::random(16) . "-avatar." . $file->getClientOriginalExtension();
        if (Storage::exists($this->model->avatar)) {
            Storage::delete($this->model->avatar);
        }
        Storage::put($filename, file_get_contents($file));

        $request->merge(['avatar' => $filename]);
        return parent::update($request, $this->model->getAuthIdentifier());
    }

    /**
     * @OA\Put(
     *     path="/users/me/reset",
     *     summary="Atualiza a senha do usuário",
     *     operationId="UpdateAuthUserPassword",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="old_password",
     *         in="query",
     *         description="Senha antiga do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Nova senha do usuário",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password_confirmation",
     *         in="query",
     *         description="Confirmação da senha",
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
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        if (!Hash::check($request->input('old_password'), Auth::user()->password)) {
            return $this->unauthorized(['error' => 'Incorrect Password']);
        }
        $password = [
            'password' => Hash::make($request->input('password'))
        ];
        Auth::user()->update($password);

        return $this->success(['res' => 'Your password has been changed.']);
    }

    /**
     * @OA\Get(
     *     path="/users/me/likes",
     *     summary="Retorna todos os livros likados do usuário logado",
     *     operationId="UserLikedBooks",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getLikedBooks()
    {
        $data = $this->model->likes()->get();

        return $this->success($data);
    }

    /**
     * @OA\Post(
     *     path="/users/me/likes/{bookId}",
     *     summary="Dá um like em um livro para o usuário logado",
     *     operationId="UserLikeBook",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="bookId",
     *         in="path",
     *         description="ID do livro",
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
    public function likeBook(Request $request, int $bookId)
    {
        $data = Book::findOrFail($bookId);

        $verify = $this->model->likes()->where('book_id', $bookId)->first();

        if ($verify) {
            $data->likes()->detach(Auth::user()->getAuthIdentifier());
            return $this->success($data);
        }

        $data->likes()->attach(Auth::user()->getAuthIdentifier());

        return $this->success($data);
    }

    /**
     * @OA\Post(
     *     path="/users/me/reports/{reportedId}",
     *     summary="Denúncia um usuário",
     *     operationId="UserReport",
     *     tags={"users"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="reportedId",
     *         in="path",
     *         description="ID do usuário que vai ser reportado",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type_id",
     *         in="query",
     *         description="ID do tipo de report",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Descrição da denúncia",
     *         required=false,
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
    public function report(Request $request, int $reportedId)
    {
        $request->merge(['reported_id' => $reportedId]);
        $this->validate($request, [
            'reported_id' => 'required|exists:users,id',
            'type_id' => 'required|exists:report_types,id',
            'description' => 'string'
        ]);

        $this->model->reports()->attach($reportedId, [
            'type_id' => $request->input('type_id'),
            'description' => $request->input('description')
        ]);

        return $this->noContent();
    }
}
