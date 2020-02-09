<?php


class UserTest extends TestCase
{
	public function testCreateUser()
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

	public function testUnauthorizedGetUser()
	{
		$this->json('GET', '/users/1');

		$this->assertResponseStatus(401);
	}
}
