<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tahun_keluaran' => $this->faker->date($format = 'Y', $max = '2023'),
            'warna' => $this->faker->colorName(),
            'harga' => $this->faker->randomFloat(2),
            'created_at' => $this->faker->date($format = 'c', $max = now()),
        ];
    }
}
