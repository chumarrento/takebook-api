<?php

namespace acceptance;

use App\Entities\Auth\User;
use App\Entities\Book\Book;
use App\Enums\Book\Status;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BookTest extends \TestCase
{
	use DatabaseTransactions;

	/** @test */
	public function canAdminFetchBooksToApprove()
	{
		$login = $this->adminLogin();

		$books = factory(User::class)->create()
			->books()->createMany(factory(Book::class, 3)->make()->toArray());

		$this->get('books/validate', [
			'Authorization' => 'Bearer ' . $login['token']
		]);

		$this->seeJsonContains(['total' => count($books)]);
	}

	/** @test */
	public function canAdminApproveBooks()
	{
		$login = $this->adminLogin();
		$book = factory(User::class)->create()->books()->create(factory(Book::class)->make()->toArray());

		$a = $this->put("books/$book->id/status", [
			'status_id' => Status::APPROVED
		], [
			'Authorization' => 'Bearer ' . $login['token']
		]);
		dd($a->response->exception);
		$this->seeJsonContains([
			'id' => $book->id,
			'status_id' => Status::APPROVED
		]);
	}
}
