<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(3)->create();

        $users = [
            [
                'name' => 'Admin 1',
                'email' => 'admin1@gmail.com',
                'password' => bcrypt('admin1'),
                'level' => 1
            ],
            [
                'name' => 'EDP 1',
                'email' => 'edp1@gmail.com',
                'password' => bcrypt('edp1'),
                'level' => 2
            ],
            [
                'name' => 'Operator 1',
                'email' => 'operator1@gmail.com',
                'password' => bcrypt('operator1'),
                'level' => 3
            ]
        ];

        collect($users)->each(function($user){
            User::create($user);
        });
    }
}
