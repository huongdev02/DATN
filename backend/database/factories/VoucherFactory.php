<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->regexify('[A-Z0-9]{10}'),
            'type' => fake()->randomElement([0, 1]),
            'discount_value' => fake()->randomFloat(2, 0, 10000),
            'description' => fake()->optional()->text(),
            'discount_min' => fake()->randomFloat(2, 0, 10000),
            'max_discount' => fake()->randomFloat(2, 0, 10000),
            'min_order_count' => fake()->numberBetween(1, 100),
            'max_order_count' => fake()->numberBetween(1, 100),
            'quantity' => fake()->numberBetween(1, 100),
            'used_times' => fake()->numberBetween(0, 50),
            'start_day' => fake()->optional()->dateTime(),
            'end_day' => fake()->optional()->dateTime(),
            'status' => fake()->randomElement([0, 1, 2, 3]),
        ];
    }
}
