<?php

use App\Entities\Report\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
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
