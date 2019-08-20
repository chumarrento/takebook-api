<?php


namespace App\Http\Controllers\Book;


use App\Entities\Book\Book;
use App\Entities\Category\Category;
use App\FieldManagers\Book\BookFieldManager;
use App\Http\Controllers\ApiController;
use App\Repositories\Book\BookRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends ApiController
{
    use ApiResponse;

    public function __construct(Book $model, BookRepository $repository, BookFieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
        $this->model = $model;
        $this->repository = $repository;
        $this->middleware('auth:api', ['only' => ['postBook']]);
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
        $request->merge(['status' => true]);
        return parent::index($request); // TODO: Change the autogenerated stub
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
     *         name="categories",
     *         in="query",
     *         description="IDs das categorias",
     *         required=true,
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
    public function postBook(Request $request)
    {
        $categories = is_array($request->input('categories'))
            ? $request->input('categories') : explode(',', $request->input('categories'));

        foreach ($categories as $category) {
            Category::findOrFail($category);
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
     * @OA\Delete(
     *     path="/books/{id}",
     *     summary="Apaga um livro",
     *     operationId="DeleteBook",
     *     tags={"books"},
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
