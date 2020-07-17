<?php

namespace unit;

use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Entities\Auth\User;

class AuthTest extends \TestCase
{
	use DatabaseTransactions;

	/** @test */
	public function canUserLoginInAppEndpoint()
	{
		$user = factory(User::class)->create();
		$credentials = [
			'email' => $user->email,
			'password' => 'secret'
		];

		$this->json('POST', '/auth/login', $credentials);

		$this->seeJsonContains(['status' => 'success']);
	}

	/** @test */
	public function canUserWithoutRegisterLogin()
	{
		$credentials = [
			'email' => 'notfound@email.com',
			'password' => 'secret'
		];

		$this->json('POST', '/auth/login', $credentials);

		$this->seeJsonContains(['error' => 'User not found'])
			->assertResponseStatus(404);
	}

	/** @test */
	public function canUserWithIncorrectPasswordLogin()
	{
		$user = factory(User::class)->create();

		$credentials = [
			'email' => $user->email,
			'password' => 'incorrectPassword'
		];

		$this->json('POST', '/auth/login', $credentials);

		$this->seeJsonContains(['error' => 'Incorrect Password'])
			->assertResponseStatus(401);
	}

	/** @test */
	public function canUserWithoutBeingAdminLoginInAdminEndpoint()
	{
		$credentials = [
			'email' => 'fulano132@email.com',
			'password' => 'secret'
		];

		$this->json('POST', '/admin/auth/login', $credentials);

		$this->assertEquals(json_encode(['error' => 'User not found']), $this->response->getContent());
	}
}
