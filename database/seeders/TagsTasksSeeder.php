<?php

namespace Database\Seeders;

use App\Models\TagsTasks;
use App\Models\Tasks;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagsTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags_tasks')->truncate();

        TagsTasks::factory()->count(100)->create();

        $tags_tasks = [
            [
                'tag_id' => 1,
                'task_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 2,
                'task_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 3,
                'task_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 4,
                'task_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 5,
                'task_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 6,
                'task_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 7,
                'task_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tag_id' => 8,
                'task_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        TagsTasks::insert($tags_tasks);
    }
}
