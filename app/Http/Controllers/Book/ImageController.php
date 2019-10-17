<?php


namespace App\Http\Controllers\Book;


use App\Entities\Book\Image;
use App\Http\Controllers\Controller;
use App\Repositories\Book\ImageRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    use ApiResponse;

    private  $repository;
    private $model;

    public function __construct(ImageRepository $repository, Image $model)
    {
        $this->middleware('auth:api');
        $this->repository = $repository;
        $this->model = $model;
    }

    /**
     * @OA\Post(
     *     path="/books/{bookId}/image/{imageId}",
     *     summary="Atualiza uma imagem de um livro",
     *     operationId="UpdateBookImage",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="bookId",
     *         in="path",
     *         description="ID do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="imageId",
     *         in="path",
     *         description="ID da imagem",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="cover_file",
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
    public function updateImage(Request $request, int $bookId, $imageId)
    {
        $request->merge([
            'book_id' => $bookId,
            'image_id' => $imageId
        ]);

        $this->validate($request, [
            'cover_file' => 'image|required',
            'book_id' => 'required|exists:books,id',
            'image_id' => 'required|exists:book_images,id'
        ]);

        $data = $this->repository->update($request->all(), $imageId);

        return $data ? $this->noContent() : $this->unauthorized();
    }

    /**
     * @OA\Delete(
     *     path="/books/{bookId}/image/{imageId}",
     *     summary="Apaga uma imagem de um livro",
     *     operationId="DeleteBookImage",
     *     tags={"books"},
     *     security={{"apiToken":{}}},
     *     @OA\Parameter(
     *         name="bookId",
     *         in="path",
     *         description="ID do livro",
     *         required=true,
     *         @OA\Schema(
     *           type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="imageId",
     *         in="path",
     *         description="ID da imagem",
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
    public function deleteImage(int $bookId, int $imageId)
    {
        $validate = $this->model->findOrFail($imageId)->book()->findOrFail($bookId);

        if ($validate->user_id !== Auth::user()->getAuthIdentifier()) {
            return $this->unauthorized();
        }

        $this->repository->delete($imageId);

        return $this->noContent();
    }
}
