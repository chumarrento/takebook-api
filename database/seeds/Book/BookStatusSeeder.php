<?php

use Illuminate\Database\Seeder;
use App\Entities\Book\Status;
use Illuminate\Support\Facades\DB;

class BookStatusSeeder extends Seeder
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
                'name' => 'Em Análise'
            ],
            [
                'name' => 'Aprovada'
            ],
            [
                'name' => 'Reprovada'
            ],
			[
				'name' => 'Vendido'
			]
        ];
        DB::statement('SET foreign_key_checks=0');

        Status::truncate();

        foreach ($data as $status) {
            Status::create($status);
        }
        DB::statement('SET foreign_key_checks=1');
    }
}
