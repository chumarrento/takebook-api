<?php

use Illuminate\Database\Seeder;
use App\Entities\Auth\User;
use Illuminate\Support\Facades\Hash;

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
                'first_name' => 'André',
                'last_name' => 'Felipe',
                'email' => 'slamermao@coloca.com',
                'password' => Hash::make('123123'),
                'is_admin' => true
            ]
        ];

        User::truncate();

        foreach ($data as $login) {
            User::create($login);
        }
    }
}
