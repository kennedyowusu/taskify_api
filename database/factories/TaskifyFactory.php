<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Taskify;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskifyFactory extends Factory
{
    protected $model = Taskify::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'is_completed' => $this->faker->boolean(30), // 30% chance of being true
            'user_id' => User::factory(), // Create a related user
        ];
    }
}
