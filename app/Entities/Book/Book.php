<?php


namespace App\Entities\Book;


use App\Entities\Category\Category;
use App\Entities\Notification;
use App\Entities\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'title',
		'author',
		'description',
		'price',
		'approved_at',
		'condition_id',
		'status_id',
		'user_id',
		'solded_at'
	];

	protected $appends = ['categories', 'count_likes', 'owner', 'covers_url', 'viewer_liked'];

	public function status()
	{
		return $this->belongsTo(Status::class, 'status_id', 'id');
	}

	public function conditions()
	{
		return $this->belongsTo(Condition::class, 'condition_id', 'id');
	}

	public function notification()
	{
		return $this->hasMany(Notification::class, 'book_id', 'id');
	}

	public function getCategoriesAttribute()
	{
		$data = $this->categories()->getResults();

		foreach ($data as $category) {
			unset($category['pivot']);
		}
		return $data;
	}

	public function categories()
	{
		return $this->belongsToMany(
			Category::class,
			'book_categories',
			'book_id',
			'category_id'
		)->withTimestamps();
	}

	public function getCountLikesAttribute()
	{
		return $this->likes()->count();
	}

	public function likes()
	{
		return $this->belongsToMany(
			User::class,
			'user_like_books',
			'book_id',
			'user_id'
		)->withTimestamps();
	}

	public function getOwnerAttribute()
	{
		return $this->user()->getResults();
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function buyer()
	{
		return $this->hasOne(HasBuyer::class, 'book_id', 'id');
	}

	public function getCoversUrlAttribute()
	{
		$data = [];
		foreach ($this->images()->getResults() as $image) {
			$url = Storage::url($image->cover);
			$data[] = [
				'url' => $url,
				'order' => $image->order,
				'image_id' => $image->id
			];
		}

		return $data;
	}

	public function images()
	{
		return $this->hasMany(Image::class, 'book_id', 'id');
	}

	public function getViewerLikedAttribute()
	{
		if (Auth::check()) {
			$userLike = $this->likes()->where('user_id', Auth::user()->id)->first();

			if ($userLike) {
        return true;
      }
		}
		return false;
	}
}
