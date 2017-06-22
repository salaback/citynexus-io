<?php

namespace App\Notifications;

use App\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AssignedNewTask extends Notification
{
    use Queueable;
    private $clickBack;
    private $task;
    private $client_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task = $task->name;
        $this->client_id = config('client.id');

        switch ($task->taskList->taskable_type)
        {
            case 'App\PropertyMgr\Model\Property':
                $this->clickBack = config('client.domain') . route('properties.show', [$task->taskList->taskable_id]) . '?tab=tasks';
                break;
            case 'App\PropertyMgr\Model\Entity':
                $this->clickBack = config('client.domain') . route('entity.show', [$task->taskList->taskable_id]) . '?tab=tasks';
                break;
        }

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You have been assigned a new task')
                    ->action($this->task, $this->clickBack);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'You have been assigned a new task',
            'task' => $this->task,
            'clickBack' => $this->clickBack
        ];
    }
}
