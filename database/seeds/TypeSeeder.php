<?php

use App\Entities\Report\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
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
                'name' => 'Racismo/Homofobia/Sexismo'
            ],
            [
                'name' => 'Ofensa'
            ],
            [
                'name' => 'Produto'
            ],
            [
                'name' => 'Imagens ofensivas'
            ],
            [
                'name' => 'Mensagens inapropriadas'
            ],
        ];
        DB::statement('SET foreign_key_checks=0');
        Type::truncate();

        foreach ($data as $type) {
            Type::create($type);
        }
        DB::statement('SET foreign_key_checks=1');
    }
}
