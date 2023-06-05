<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MotorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mesin' => $this->faker->numberBetween(49, 2100),
            'tipe_suspensi' => $this->faker->randomElement(['telescopic', 'usd']),
            'tipe_transmisi' => $this->faker->randomElement(['manual', 'semi-manual', 'automatic']),
            'kendaraan_id' => $this->faker->randomElement([]),
            'created_at' => $this->faker->date($format = 'Y-m-d\TH:i:sP', $max = now()),
        ];
    }
}
