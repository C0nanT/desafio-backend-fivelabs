<?php

namespace App\Jobs;

use App\Models\Tasks;
use App\Models\User;
use App\Notifications\TaskDueNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckTasksDueNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of seconds before the job should timeout
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Number of attempts to process the job
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job
     *
     * @var array
     */
    public $backoff = [5, 10, 15];

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $twoDaysFromNow = Carbon::now()->addDays(2)->toDateString();
        
        $tasks = Tasks::where('due_date', '<=', $twoDaysFromNow)
            ->whereNotNull('responsible')
            ->where('status', '!=', 'completed')
            ->get();
            
        Log::info('Verificando tarefas prestes a vencer: ' . $tasks->count() . ' tarefas encontradas.');
            
        foreach ($tasks as $task) {
            $user = User::find($task->responsible);
            
            if ($user) {
                try {
                    $user->notify(new TaskDueNotification($task));
                    Log::info("Notificação enviada para o usuário #{$user->id} sobre a tarefa #{$task->id} - {$task->title}");
                } catch (\Exception $e) {
                    Log::error("Erro ao enviar notificação para o usuário #{$user->id}: " . $e->getMessage());
                }
            } else {
                Log::warning("Usuário responsável #{$task->responsible} não encontrado para a tarefa #{$task->id}");
            }
        }
    }
}
