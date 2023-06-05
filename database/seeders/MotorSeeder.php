<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motor;

class MotorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Motor::factory()->count(10)->create();
    }
}
