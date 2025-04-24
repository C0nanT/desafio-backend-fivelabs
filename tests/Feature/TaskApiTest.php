<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tasks;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TaskApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test if a user can create a task.
     *
     * @return void
     */
    public function test_user_can_create_a_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $taskData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'in_progress',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => $user->id
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'priority',
                    'responsible',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'id'
                ]
            ])

            ->assertJson([
                'message' => 'Task created successfully',
                'data' => [
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'status' => $taskData['status'],
                    'priority' => $taskData['priority'],
                    'responsible' => $taskData['responsible'],
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => $taskData['title'],
            'description' => $taskData['description'],
            'status' => $taskData['status'],
        ]);
    }

    /**
     * Test if a user can view a task.
     *
     * @return void
     */
    public function test_user_can_view_a_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $task = Tasks::factory()->create([
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'in_progress',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => $user->id,
            'created_by' => $user->id
        ]);

        $response = $this->getJson('/api/tasks/' . $task->id);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'priority',
                    'responsible',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'id'
                ]
            ])
            ->assertJson([
                'data' => [
                    'title' => $task->title,
                ]
            ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
        ]);
    }

    /**
     * Test if a user can't view a task if not responsible or created by.
     *
     * @return void
     */
    public function test_user_cant_view_a_task_if_not_responsible_or_created_by()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $task = Tasks::factory()->create([
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'in_progress',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => User::factory()->create()->id,
            'created_by' => User::factory()->create()->id
        ]);

        $response = $this->getJson('/api/tasks/' . $task->id);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found'
            ]);
            
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
        ]);
    }

    /**
     * Test if a user can update a task.
     *
     * @return void
     */
    public function test_user_can_update_a_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $task = Tasks::factory()->create([
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'in_progress',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => $user->id,
            'created_by' => $user->id
        ]);

        $updateData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'completed',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => $user->id
        ];

        $response = $this->putJson('/api/tasks/' . $task->id, $updateData);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'priority',
                    'responsible',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'id'
                ]
            ])
            ->assertJson([
                'message' => 'Task updated successfully',
                'data' => [
                    'title' => $updateData['title'],
                    'description' => $updateData['description'],
                    'status' => $updateData['status'],
                    'priority' => $updateData['priority'],
                    'responsible' => $updateData['responsible'],
                ]
            ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $updateData['title'],
            'description' => $updateData['description'],
            'status' => $updateData['status'],
        ]);
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
        ]);
    }

      /**
     * Test if a user can't update a task if not responsible or created by.
     *
     * @return void
     */
    public function test_user_cant_update_a_task_if_not_responsible_or_created_by()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $task = Tasks::factory()->create([
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'in_progress',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => User::factory()->create()->id,
            'created_by' => User::factory()->create()->id
        ]);

        $updateData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'completed',
            'due_date' => $this->faker->date('Y-m-d'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'responsible' => User::factory()->create()->id
        ];

        $response = $this->putJson('/api/tasks/' . $task->id, $updateData);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Task not found'
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
        ]);
        
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'title' => $updateData['title'],
            'description' => $updateData['description'],
            'status' => $updateData['status'],
        ]);
    }
}