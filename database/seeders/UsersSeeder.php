<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('admin'),
            ],
            [
                'name' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'role' => 'superadmin',
                'password' => bcrypt('superadmin'),
            ],
            [
                'name' => 'pegawai',
                'email' => 'pegawai@gmail.com',
                'role' => 'pegawai',
                'password' => bcrypt('pegawai'),
            ],

        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
