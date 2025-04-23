<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->truncate();

        $tasks = [
            [
                'title' => 'Finalizar documentação API',
                'description' => 'Completar a documentação da API REST para o projeto Laravel CRUD',
                'status' => 'in_progress',
                'due_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'created_by' => 1,
                'priority' => 'high',
                'responsible' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Implementar autenticação JWT',
                'description' => 'Adicionar autenticação JWT ao projeto Laravel CRUD',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'created_by' => 1,
                'priority' => 'medium',
                'responsible' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Criar testes automatizados',
                'description' => 'Desenvolver testes automatizados para o projeto Laravel CRUD',
                'status' => 'completed',
                'due_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'created_by' => 1,
                'priority' => 'low',
                'responsible' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Revisar código do projeto',
                'description' => 'Realizar uma revisão completa do código do projeto Laravel CRUD',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'created_by' => 1,
                'priority' => 'medium',
                'responsible' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Configurar CI/CD',
                'description' => 'Configurar integração contínua e entrega contínua para o projeto Laravel CRUD',
                'status' => 'in_progress',
                'due_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'created_by' => 2,
                'priority' => 'high',
                'responsible' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Configurar Docker',
                'description' => 'Criar um ambiente Docker para o projeto Laravel CRUD',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'created_by' => 2,
                'priority' => 'low',
                'responsible' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Implementar REDIS',
                'description' => 'Adicionar suporte ao REDIS para cache e filas no projeto Laravel CRUD',
                'status' => 'completed',
                'due_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'created_by' => 2,
                'priority' => 'medium',
                'responsible' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Criar documentação do banco de dados',
                'description' => 'Desenvolver documentação detalhada do banco de dados utilizado no projeto Laravel CRUD',
                'status' => 'pending',
                'due_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
                'created_by' => 3,
                'priority' => 'high',
                'responsible' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Task::insert($tasks);
    }
}
