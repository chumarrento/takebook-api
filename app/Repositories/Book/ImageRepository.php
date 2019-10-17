<?php


namespace App\Repositories\Book;


use App\Entities\Book\Image;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Image();
    }

    public function update(array $data, $id)
    {
        $image = $this->find($id);

        if($image->book_id !== $data['book_id'] || $image->book->user_id !== Auth::user()->getAuthIdentifier()) {
            return false;
        }

        $file = $data['cover_file'];
        $filename = "covers/" . Str::random(16) . "-cover." . $file->getClientOriginalExtension();
        if (Storage::exists($image->cover)) {
            Storage::delete($image->cover);
        }
        Storage::put($filename, file_get_contents($file));

        $data['cover'] = $filename;
        return parent::update($data, $id);
    }

    public function delete($id)
    {
        $image = $this->find($id);

        if (Storage::exists($image->cover)) {
            Storage::delete($image->cover);
        }
        return parent::delete($id);
    }
}
