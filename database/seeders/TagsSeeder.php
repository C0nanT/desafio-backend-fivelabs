<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tags;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->truncate();
        
        $tags = [
            [
                'name' => 'FiveLabs <3',
                'slug' => 'fivelabs',
                'description' => 'Tasks related to FiveLabs.',
                'color' => '#8238eb',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Frontend',
                'slug' => 'frontend',
                'description' => 'Tasks related to frontend development.',
                'color' => '#33FF57',
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Backend',
                'slug' => 'backend',
                'description' => 'Tasks related to backend development.',
                'color' => '#3357FF',
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Testing',
                'slug' => 'testing',
                'description' => 'Tasks related to testing and QA.',
                'color' => '#FF33A1',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Documentation',
                'slug' => 'documentation',
                'description' => 'Tasks related to documentation.',
                'color' => '#FF33FF',
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Research',
                'slug' => 'research',
                'description' => 'Tasks related to research and development.',
                'color' => '#33FFF5',
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'Tasks related to design and UI/UX.',
                'color' => '#F5FF33',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deployment',
                'slug' => 'deployment',
                'description' => 'Tasks related to deployment and release.',
                'color' => '#FF5733',
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maintenance',
                'slug' => 'maintenance',
                'description' => 'Tasks related to maintenance and support.',
                'color' => '#33FF57',
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Security',
                'slug' => 'security',
                'description' => 'Tasks related to security and compliance.',
                'color' => '#3357FF',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Tags::insert($tags);
    }
}
