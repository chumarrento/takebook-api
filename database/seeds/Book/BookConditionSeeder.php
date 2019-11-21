<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Entities\Book\Condition;

class BookConditionSeeder extends Seeder
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
                'name' => 'Novo'
            ],
            [
                'name' => 'Semi-novo'
            ],
            [
                'name' => 'Usado'
            ]
        ];
        DB::statement('SET foreign_key_checks=0');

        Condition::truncate();

        foreach ($data as $condition) {
            Condition::create($condition);
        }
        DB::statement('SET foreign_key_checks=1');
    }
}
