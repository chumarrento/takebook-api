<?php


namespace App\Repositories\User;


use App\Entities\User\User;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
		DB::beginTransaction();
		try {
			$data['password'] = Hash::make($data['password']);
			if (array_key_exists('avatar_file', $data)) {
				$file = $data['avatar_file'];
				$fileName = "avatars/" . Str::random(16) . "-avatar." . $file->getClientOriginalExtension();
				$data['avatar'] = $fileName;
			}

			$user = parent::create($data);

			if (array_key_exists('address', $data)) {
				$user->address()->create($data['address']);
			}
			DB::commit();

			if (array_key_exists('avatar', $data)) {
				Storage::put($fileName, file_get_contents($file));
			}
			return $user;
		} catch (\Exception $exception) {
			DB::rollBack();
			return false;
		}
	}

	public function update(array $data, $id)
	{
		if (!Auth::check() && !Auth::user()->is_admin) {
			unset($data['is_admin']);
		}
		unset($data['password']);
		DB::beginTransaction();
		try {
			$user = parent::update($data, $id);

			if (array_key_exists('address', $data)) {
				if ($user->address) {
					$user->address()->update($data['address']);
				} else {
					$user->address()->create($data['address']);
				}
			}
			DB::commit();

			return $user;
		} catch (\Exception $exception) {
			DB::rollBack();
			return false;
		}
	}
}
