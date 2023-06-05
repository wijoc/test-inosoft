<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'username' => 'test1',
                'password' => 'test1'
            ],
            [
                'username' => 'test2',
                'password' => 'test2'
            ]
        ]);
    }
}
