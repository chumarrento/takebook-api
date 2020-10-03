<?php


namespace App\Repositories\Book;


use App\Entities\Book\Book;
use App\Enums\Book\Status;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Repository;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookRepository extends Repository
{
	public function __construct()
	{
		$this->model = new Book();
	}

	public function create(array $data)
	{
		unset($data['status_id']);
		DB::beginTransaction();
		try {
			$book = parent::create($data);

			$this->sync($book->id, 'categories', $data['categories']);
			foreach ($data['images'] as $key => $image) {
				$fileName = "covers/" . Str::random(16) . "-cover." . $image->getClientOriginalExtension();

				Storage::put($fileName, file_get_contents($image));

				$book->images()->create([
					'cover' => $fileName,
					'order' => $key
				]);
			}
			$this->setNotification($book);
			DB::commit();
			return $book;
		} catch (\Exception $exception) {
			DB::rollBack();
			return false;
		}
	}

	public function update(array $data, $id)
	{
		$book = $this->model->find($id);

		if (!Auth::user()->is_admin) {
			unset($data['status_id']);
			unset($data['approved_at']);
			if ($book->user_id != Auth::user()->getAuthIdentifier() || $book->status_id == Status::APPROVED) {
				return false;
			}
		}


		return parent::update($data, $id);
	}

	public function delete($id)
	{
		$book = $this->model->find($id);
		if ($book->user_id !== Auth::user()->getAuthIdentifier()) {
			return false;
		}
		$images = $book->images;
		if ($images) {
			foreach ($images as $image) {
				if (Storage::exists($image->cover)) {
					Storage::delete($image->cover);
				}
			}
		}
		return parent::delete($id); // TODO: Change the autogenerated stub
	}

	public function setNotification($book)
	{
		$notification = [
			'reason' => 'BOOK_CREATED',
			'book_id' => $book->id,
			'user_id' => $book->user_id
		];
		(new NotificationRepository())->create($notification);
	}
}
