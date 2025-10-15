<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerMeasurement>
 */
class CustomerMeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->unique()->numerify('####'),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'kameezlength' => $this->faker->randomElement(['40', '42', '44', '46', '48']),
            'teera' => $this->faker->randomElement(['22', '24', '26', '28']),
            'baazo' => $this->faker->randomElement(['18', '19', '20', '21']),
            'chest' => $this->faker->randomElement(['20', '21', '22', '23']),
            'neck' => $this->faker->randomElement(['14', '15', '16', '17']),
            'daman' => $this->faker->randomElement(['22', '23', '24', '25']),
            'kera' => $this->faker->randomElement(['gol', 'chauka', 'sada']),
            'shalwar' => $this->faker->randomElement(['40', '42', '44', '46']),
            'pancha' => $this->faker->randomElement(['10', '12', '14', '16']),
            'pocket' => $this->faker->randomElement(['1', '2', '3']),
            'note' => $this->faker->sentence(),
            'created_by' => 1,
            'updated_by' => null,
        ];
    }
}
