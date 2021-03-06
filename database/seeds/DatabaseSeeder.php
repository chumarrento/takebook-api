<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		 $this->call(UsersSeeder::class);
         $this->call(CategoriesSeeder::class);
         $this->call(TypeSeeder::class);
         $this->call(StatusSeeder::class);
         $this->call(BookStatusSeeder::class);
         $this->call(BookConditionSeeder::class);
    }
}
