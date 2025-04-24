<?php

namespace Database\Factories;

use App\Models\TagsTasks;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TagsTasks>
 */
class TagsTasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TagsTasks::class;

    public function definition(): array
    {
        return [
            'tag_id' => $this->faker->numberBetween(1, 10),
            'task_id' => $this->faker->numberBetween(1, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
}