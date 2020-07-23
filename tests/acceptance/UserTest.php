<?php

namespace acceptance;

use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Entities\Auth\User;

class UserTest extends \TestCase
{
	use DatabaseTransactions;

	/** @test */
	public function canICreateUser()
	{
		$user = [
			'first_name' => 'Fake',
			'last_name' => 'Name',
			'email' => 'teste@email1.com',
			'password' => 'secret'
		];

		$response = $this->json('POST', '/users', $user);

		$response->assertResponseStatus(201);
	}

	/** @test */
	public function canIFetchUsersWithoutAuthentication()
	{
		$this->json('GET', '/users');

		$this->assertResponseStatus(401);
	}

	/** @test */
	public function canIFetchSpecificUserWithoutAuthentication()
	{
		$this->json('GET', '/users/1');

		$this->assertResponseStatus(401);
	}

	/** @test */
	public function canIFetchUsersLoggedInWithoutBeingAdmin()
	{
		$login = $this->userLogin();

		$this->json('GET', '/users', [], [
			'Authorization' => "Bearer " . $login['token']
		]);

		$this->assertResponseStatus(401);
	}

	/** @test */
	public function canIFetchUsersBeingAdmin()
	{
		$login = $this->adminLogin();

		$this->json('GET', '/users', [], [
			'Authorization' => "Bearer " . $login['token']
		]);

		$this->seeJsonContains(['current_page' => 1])
			->assertResponseStatus(200);
	}

	/** @test */
	public function canIFetchSpecificUserBeingAdmin()
	{
		$login = $this->adminLogin();
		$user = factory(User::class)->create();

		$this->json('GET', "/users/$user->id", [], [
			'Authorization' => "Bearer " . $login['token']
		]);

		$this->seeJsonContains(['id' => $user->id])
			->assertResponseStatus(200);
	}

	/** @test */
	public function canIUpdateMyLastName()
	{
		$login = $this->userLogin();

		$this->json('PUT', '/users/me', [
			'last_name' => 'da Silva'
		],[
			'Authorization' => 'Bearer ' . $login['token']
		]);


		$this->seeJsonContains(['last_name' => 'da Silva'])
			->assertResponseStatus(200);
	}

	/** @test */
	public function canIDeleteSpecificUserBeingAdmin()
	{
		$login = $this->adminLogin();
		$user = factory(User::class)->create();

		$this->delete( "/users/$user->id", [], [
			'Authorization' => "Bearer " . $login['token']
		]);

		$this->assertResponseStatus(204);

		$this->missingFromDatabase('users', [
			'id' => $user->id,
			'email' => $user->name
		]);
	}
}
