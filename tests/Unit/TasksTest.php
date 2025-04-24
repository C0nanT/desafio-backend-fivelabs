<?php

namespace Tests\Unit;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_task()
    {
        $user = User::factory()->create();
        $responsibleUser = User::factory()->create();

        $taskData = [
            'title' => 'Teste de Tarefa',
            'description' => 'Esta é uma descrição de teste',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'created_by' => $user->id,
            'priority' => 'medium',
            'responsible' => $responsibleUser->id,
        ];

        $task = Tasks::create($taskData);

        $this->assertInstanceOf(Tasks::class, $task);
        $this->assertEquals('Teste de Tarefa', $task->title);
        $this->assertEquals('Esta é uma descrição de teste', $task->description);
        $this->assertEquals('pending', $task->status);
        $this->assertEquals('medium', $task->priority);
        $this->assertEquals($user->id, $task->created_by);
        $this->assertEquals($responsibleUser->id, $task->responsible);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $user = User::factory()->create();
        $responsibleUser = User::factory()->create();

        $task = Tasks::factory()->create([
            'created_by' => $user->id,
            'responsible' => $responsibleUser->id,
        ]);

        $updatedData = [
            'title' => 'Tarefa Atualizada',
            'description' => 'Descrição atualizada',
            'status' => 'completed',
            'due_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'priority' => 'high',
        ];

        $task->update($updatedData);

        $this->assertEquals('Tarefa Atualizada', $task->title);
        $this->assertEquals('Descrição atualizada', $task->description);
        $this->assertEquals('completed', $task->status);
        $this->assertEquals('high', $task->priority);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $user = User::factory()->create();
        $responsibleUser = User::factory()->create();

        $task = Tasks::factory()->create([
            'created_by' => $user->id,
            'responsible' => $responsibleUser->id,
        ]);

        $taskId = $task->id;

        $task->delete();

        $this->assertDatabaseMissing('tasks', ['id' => $taskId]);
    }

    /** @test */
    public function it_can_retrieve_a_task()
    {
        $user = User::factory()->create();
        $responsibleUser = User::factory()->create();

        $task = Tasks::factory()->create([
            'created_by' => $user->id,
            'responsible' => $responsibleUser->id,
        ]);

        $retrievedTask = Tasks::find($task->id);

        $this->assertEquals($task->title, $retrievedTask->title);
        $this->assertEquals($task->description, $retrievedTask->description);
        $this->assertEquals($task->status, $retrievedTask->status);
        $this->assertEquals($task->priority, $retrievedTask->priority);
    }

    /** @test */
    public function it_can_list_all_tasks()
    {
        $user = User::factory()->create();
        $responsibleUser = User::factory()->create();

        $task1 = Tasks::factory()->create([
            'created_by' => $user->id,
            'responsible' => $responsibleUser->id,
        ]);

        $task2 = Tasks::factory()->create([
            'created_by' => $user->id,
            'responsible' => $responsibleUser->id,
        ]);

        $tasks = Tasks::all();

        $this->assertCount(2, $tasks);
        $this->assertTrue($tasks->contains($task1));
        $this->assertTrue($tasks->contains($task2));
        $this->assertEquals($task1->title, $tasks[0]->title);
    }
}
