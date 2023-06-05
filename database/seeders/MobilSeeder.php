<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mobil;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mobil::factory()->count(10)->create();
    }
}
