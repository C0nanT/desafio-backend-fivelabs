<?php

namespace App\Notifications;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString; 

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Tasks $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */    
    public function toMail(object $notifiable): MailMessage
    {
        $priorityColors = [
            'low' => '#28a745',
            'medium' => '#ffc107', 
            'high' => '#dc3545'
        ];
    
        $priorityColor = $priorityColors[$this->task->priority] ?? '#007bff'; 
        $createdBy = User::find($this->task->created_by)->name ?? 'Desconhecido';
    
        return (new MailMessage)
                    ->subject('Nova tarefa atribuída: ' . $this->task->title)
                    ->greeting('Olá ' . $notifiable->name . '!')
                    ->line('Uma nova tarefa foi atribuída a você.')
                    ->line('**Título:** ' . $this->task->title)
                    ->line('**Descrição:** ' . $this->task->description)
                    ->line(new HtmlString(
                        '**Prioridade:** <span style="color: ' . $priorityColor . '; font-weight: bold;">' . 
                        ucfirst($this->task->priority) . 
                        '</span>'
                    ))
                    ->line('**Criada por:** ' . $createdBy)
                    ->line('**Status:** ' . ucfirst(str_replace('_', ' ', $this->task->status)))
                    ->when($this->task->due_date, function ($message) {
                        return $message->line('**Data de vencimento:** ' . $this->task->due_date->format('d/m/Y'));
                    })
                    ->action('Ver tarefa', 'http://localhost:8000/api/tasks/' . $this->task->id)
                    ->line('Obrigado por usar nosso sistema!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'created_by' => $this->task->created_by,
        ];
    }
}
