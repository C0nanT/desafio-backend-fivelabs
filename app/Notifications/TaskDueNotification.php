<?php

namespace App\Notifications;

use App\Models\Tasks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TaskDueNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
        $dueDate = $this->task->due_date->format('d/m/Y');

        $priorityColors = [
            'low' => '#28a745',
            'medium' => '#ffc107', 
            'high' => '#dc3545'
        ];

        $priorityColor = $priorityColors[$this->task->priority] ?? '#007bff'; 

        return (new MailMessage)
            ->subject('Tarefa Próxima do Vencimento: ' . $this->task->title)
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line('Este é um lembrete que a tarefa abaixo está próxima do vencimento:')
            ->line('Título: ' . $this->task->title)
            ->line('Data de vencimento: ' . $dueDate)
            ->line(new HtmlString(
                '**Prioridade:** <span style="color: ' . $priorityColor . '; font-weight: bold;">' . 
                ucfirst($this->task->priority) . 
                '</span>'
            ))
            ->action('Ver Tarefa', url('/tasks/' . $this->task->id))
            ->line('Por favor, verifique e tome as ações necessárias.');
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
            'due_date' => $this->task->due_date->format('Y-m-d'),
        ];
    }
}