<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
                'password' => Hash::make('test1password')
            ],
            [
                'username' => 'test2',
                'password' => Hash::make('test2password')
            ]
        ]);
    }
}
