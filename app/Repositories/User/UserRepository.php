<?php


namespace App\Repositories\User;


use App\Entities\User\User;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository
{
	public function __construct()
	{
		$this->model = new User();
	}

	public function create(array $data)
	{
		if (!Auth::check() || !Auth::user()->is_admin) {
			unset($data['is_admin']);
		}

		$data['password'] = Hash::make($data['password']);
		$user = parent::create($data);

		if (array_key_exists('address', $data)) {
			$user->address()->create($data['address']);
		}

		return $user;
	}

	public function update(array $data, $id)
	{
		if (!Auth::check() && !Auth::user()->is_admin) {
			unset($data['is_admin']);
		}
		unset($data['password']);

		$user = parent::update($data, $id);

		if (array_key_exists('address', $data)) {
			if ($user->address) {
				$user->address()->update($data['address']);
			} else {
				$user->address()->create($data['address']);
			}
		}

		return $user;
	}
}
