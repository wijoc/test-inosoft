<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mesin' => $this->faker->numberBetween(49, 10000),
            'kapasitas_penumpang' => $this->faker->numberBetween(1, 10),
            'tipe' => $this->faker->vehicleType(),
            'kendaraan_id' => $this->faker->randomElement([]),
            'created_at' => $this->faker->date($format = 'Y-m-d\TH:i:sP', $max = now()),
        ];
    }
}
