<?php

use Illuminate\Database\Seeder;
use App\Entities\Category\Category;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Terror',
            ],
            [
                'name' => 'Comédia',
			],
			[
                'name' => 'Mistério',
			],
			[
                'name' => 'Aventura',
            ],
		];
		DB::statement('SET foreign_key_checks=0');

        Category::truncate();

        foreach ($data as $category) {
            Category::create($category);
		}
		DB::statement('SET foreign_key_checks=1');
    }
}
