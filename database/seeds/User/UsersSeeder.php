<?php

use Illuminate\Database\Seeder;
use App\Entities\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
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
                'first_name' => 'Lucas',
                'last_name' => 'Pereira',
                'email' => 'lucasp28.contato@gmail.com',
                'password' => Hash::make('secret'),
                'is_admin' => true
            ],
            [
                'first_name' => 'Felipe',
                'last_name' => 'Barros',
                'email' => 'felipebarros.dev@gmail.com',
                'password' => Hash::make('12345'),
                'is_admin' => true
            ]
        ];
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();

        foreach ($data as $login) {
            User::create($login);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
