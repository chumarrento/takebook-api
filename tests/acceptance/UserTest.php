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
		$this->json('GET', '/users');

		$this->assertResponseStatus(401);
	}

	/** @test */
	public function canIFetchUsersLoggedInWithoutBeingAdmin()
	{
		$user = factory(User::class)->create();

		$credentials = [
			'email' => $user->email,
			'password' => 'secret'
		];

		$result = $this->json('POST', '/auth/login', $credentials);
		$responseData = json_decode($result->response->getContent(), true);

		$this->json('GET', '/users', [], [
			'Authorization' => "Bearer " . $responseData['token']
		]);

		$this->assertResponseStatus(401);
	}

	/** @test */
	public function canIFetchUsersBeingAdminUser()
	{
		$user = factory(User::class)->create(['is_admin' => true]);

		$credentials = [
			'email' => $user->email,
			'password' => 'secret'
		];

		$result = $this->json('POST', '/auth/login', $credentials);
		$responseData = json_decode($result->response->getContent(), true);

		$this->json('GET', '/users', [], [
			'Authorization' => "Bearer " . $responseData['token']
		]);

		$this->seeJsonContains(['current_page' => 1])
			->assertResponseStatus(200);
	}
}
