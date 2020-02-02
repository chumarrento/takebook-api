<?php


namespace App\Repositories\Notification;


use App\Entities\Notification;
use App\Repositories\Repository;

class NotificationRepository extends Repository
{
	public function __construct()
	{
		$this->model = new Notification();
	}

	public function create(array $data)
	{
		return parent::create($data);
	}
}
