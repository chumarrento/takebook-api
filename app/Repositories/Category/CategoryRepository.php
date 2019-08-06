<?php


namespace App\Repositories\Category;


use App\Entities\Category\Category;
use App\Repositories\Repository;

class CategoryRepository extends Repository
{
    public function __construct()
    {
        $this->model = new Category();
    }
}
