<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'user_id' => User::factory(),
            'slug' => Str::slug($this->faker->unique->sentence()),
            'approved_at' => $this->faker->optional()->dateTimeThisYear(),
            // 'created_at' => $this->faker->dateTimeBetween('-5 month', 'now'),
        ];
    }
}
