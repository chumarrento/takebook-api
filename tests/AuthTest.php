<?php


class AuthTest extends TestCase
{
	public function testAppLogin()
	{
		$credentials = [
			'email' => 'fulano132@email.com',
			'password' => 'secret'
		];

		$this->json('POST', '/auth/login', $credentials);

		$this->assertResponseOk();
	}

	public function testNotFoundUserLogin()
	{
		$credentials = [
			'email' => 'notfound@email.com',
			'password' => 'secret'
		];

		$this->json('POST', '/auth/login', $credentials);

		$this->assertResponseStatus(404);
	}

	public function testIncorrectPassword()
	{
		$credentials = [
			'email' => 'fulano132@email.com',
			'password' => 'incorrectPassword'
		];

		$this->json('POST', '/auth/login', $credentials);

		$this->assertResponseStatus(401);
	}

	public function testNormalUserTryLoginOnTheAdminEndpoint()
	{
		$credentials = [
			'email' => 'fulano132@email.com',
			'password' => 'secret'
		];

		$this->json('POST', '/admin/auth/login', $credentials);

		$this->assertEquals(json_encode(['error' => 'User not found']), $this->response->getContent());
	}
}
