<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->sentence(),
            'author' => $this->faker->unique()->name(),
            'release_date' => now()->format('Y-m-d'),
            'account_id' => Account::factory(),
        ];
    }
}
