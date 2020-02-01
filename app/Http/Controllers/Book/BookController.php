<?php


namespace App\Http\Controllers\Book;


use App\Entities\Book\Book;
use App\Entities\Category\Category;
use App\Enums\Book\Status;
use App\FieldManagers\Book\BookFieldManager;
use App\Http\Controllers\ApiController;
use App\Repositories\Book\BookRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BookController extends ApiController
{
    use ApiResponse;

    public function __construct(Book $model, BookRepository $repository, BookFieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
        $this->model = $model;
        $this->repository = $repository;
        $this->middleware('auth:api', ['except' => ['getBooks', 'getHighlightsBooks']]);
        $this->middleware('admin', ['only' => ['getBooksToValidate', 'status']]);
    }

    /**
     * @OA\Get(
     *     path="/books",
     *     summary="Lista todos os livros",
     *     operationId="GetBooks",
     *     tags={"books"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Nome do livro",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Autor do livro",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID do anunciante",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Ordernar pela data de aprovação",
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
    public function getBooks(Request $request)
    {
        return parent::index($request); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Get(
     *     path="/books/validate",
     *     summary="Lista todos os livros que precisam ser validados",
     *     operationId="GetBooksToValidate",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getBooksToValidate()
    {
        $data = $this->repository->findAll();
        return $this->success($data);
    }
	// TODO endpoints para livros aprovados e outra pra rejeitados
    /**
     * @OA\Get(
     *     path="/books/week",
     *     summary="Lista todos os livros aprovados na semana",
     *     operationId="GetWeeklyBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getWeeklyBooks()
    {
        $date = Carbon::now()->startOfWeek()->endOfWeek();
        $books = $this->model->where('status_id', Status::APPROVED)->get();
        $data = [];
        foreach ($books as $book) {
            if ($date->diffInWeeks($book->approved_at) == 0) {
                array_push($data, $book);
            }
        }

        $data['count'] = count($data);
        return $this->success($data);
    }

    /**
     * @OA\Get(
     *     path="/books/highlights",
     *     summary="Lista os livros ordenados pela quantidade de likes",
     *     operationId="GetHighlightsBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function getHighlightsBooks()
    {
        $data = $this->model->where('status_id', Status::APPROVED)->withCount('likes')
            ->orderBy('likes_count', 'desc')->get();

        return $this->success($data);
    }

    /**
     * @OA\Post(
     *     path="/books",
     *     summary="Cria um novo livro",
     *     operationId="PostBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Nome do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Autor do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Descrição do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Preço do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="condition_id",
     *         in="query",
     *         description="ID da estado do livro (1 - Novo, 2 - Semi-novo, 3 - Usado)",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="categories",
     *         in="query",
     *         description="IDs das categorias",
     *         required=true,
     *         @OA\Schema(
     *           type="array",
     *           @OA\Items(type="string")
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="images[1]",
     *                     type="file"
     *                 ),
     *                 @OA\Property(
     *                     property="images[2]",
     *                     type="file"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function postBook(Request $request)
    {
        $categories = is_array($request->input('categories'))
            ? $request->input('categories') : explode(',', $request->input('categories'));

        foreach ($categories as $category) {
            $category = ['category_id' => $category];
            $validator = Validator::make($category, ['category_id' => 'required|exists:categories,id']);

            if ($validator->fails()) {
                return $this->unprocessable($validator->errors()->messages());
            }
        }

        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $image = ['image' => $image];
                $validator = Validator::make($image, ['image' => 'required|image']);

                if ($validator->fails()) {
                    return $this->unprocessable($validator->errors()->messages());
                }
            }
        }

        $request->merge([
            'user_id' => Auth::user()->getAuthIdentifier(),
            'categories' => $categories
        ]);

        return parent::store($request);

    }

    /**
     * @OA\Get(
     *     path="/books/{id}",
     *     summary="Lista um livro",
     *     operationId="GetBook",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
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
    public function getBook(Request $request, int $id)
    {
        return parent::show($request, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Put(
     *     path="/books/{id}",
     *     summary="Atualiza um livro",
     *     operationId="PutBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Nome do livro",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Autor do livro",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Descrição do livro",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Preço do livro",
     *         required=false,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="categories",
     *         in="query",
     *         description="IDs das categorias",
     *         required=false,
     *         @OA\Schema(
     *           type="array",
     *           @OA\Items(type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="...",
     *     ),
     *  )
     */
    public function putBook(Request $request, int $id)
    {
        return parent::update($request, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Put(
     *     path="/books/{id}/status",
     *     summary="Atualiza o status de um livro",
     *     operationId="PutStatusBooks",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status_id",
     *         in="query",
     *         description="Status do livro (só para admin)",
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
    public function changeStatus(Request $request, int $id)
    {
        $request->merge(['approved_at' => Carbon::now()]);
        return parent::update($request, $id); // TODO: Change the autogenerated stub
    }

    /**
     * @OA\Delete(
     *     path="/books/{id}",
     *     summary="Apaga um livro",
     *     operationId="DeleteBook",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="id",
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
    public function deleteBook(int $id)
    {
        return parent::destroy($id); // TODO: Change the autogenerated stub
    }
}
