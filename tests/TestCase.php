<?php

use App\Entities\Auth\User;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function adminLogin()
	{
		$user = factory(User::class)->create(['is_admin' => true]);

		$credentials = [
			'email' => $user->email,
			'password' => 'secret'
		];

		$result = $this->json('POST', 'admin/auth/login', $credentials);
		return json_decode($result->response->getContent(), true);
	}

	public function userLogin()
	{
		$user = factory(User::class)->create();

		$credentials = [
			'email' => $user->email,
			'password' => 'secret'
		];

		$result = $this->json('POST', 'auth/login', $credentials);
		return json_decode($result->response->getContent(), true);
	}
}
