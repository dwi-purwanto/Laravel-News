<?php

namespace Database\Seeders;

use App\Models\User;
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

        foreach ($data as $key => $value) {
            $user = new User();
            $user->name = $value['name'];
            $user->email = $value['email'];
            $user->password = $value['password'];
            $user->role = $value['role'];
            $user->save();
        }
    }
}
