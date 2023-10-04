<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CreateUserSeeder extends Seeder
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
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => bcrypt(12345),
                'role' => 'admin'
            ],
            [
                'name' => 'Jhon',
                'email' => 'jhon@test.com',
                'password' => bcrypt(12345),
                'role' => 'user'
            ]
        ];
    }
}
