<?php

namespace App\Console\Commands;

use App\Jobs\CheckTasksDueNotification;
use Illuminate\Console\Command;

class CheckTasksDue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-due {--loop : Execute o comando em loop com intervalos de verificação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check tasks due in 2 days and send notifications to responsible users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('loop')) {
            $this->info('Iniciando verificação de tarefas em loop. Pressione Ctrl+C para sair.');
            
            while (true) {
                $this->checkTasks();
                $this->info('Aguardando 5 segundos para próxima verificação...');
                sleep(5); 
            }
        } else {
            $this->checkTasks();
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Executa a verificação de tarefas
     */
    private function checkTasks()
    {
        $this->info('Verificando tarefas a vencer...');
        
        CheckTasksDueNotification::dispatch();
        
        $this->info('Job de verificação de tarefas enfileirado com sucesso!');
    }
}