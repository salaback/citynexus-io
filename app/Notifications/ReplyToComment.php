<?php

namespace App\Notifications;

use App\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyToComment extends Notification
{
    use Queueable;

    private $preview;
    private $originalPreview;
    private $from;
    private $clickBack;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        if($comment->title != null) $this->preview = $comment->title;
        else $this->preview = str_limit($comment->comment, 100, '...');

        if($comment->replyTo->title != null) $this->preview = $comment->replyTo->title;
        else $this->originalPreview = str_limit($comment->replyTo->comment, 100, '...');

        $this->from = $comment->poster->fullname;

        switch ($comment->cn_commentable_type)
        {
            case 'CityNexus\PropertyMgr\Property':
                $this->clickBack = route('properties.show', [$comment->cn_commentable_id]) . '?tab=comments#comment-' . $comment->reply_to;
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
                    ->line('A reply has been posted to your comment.')
                    ->action('Notification Action', $this->clickBack);
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
            'preview' => $this->preview,
            'originalPreview' => $this->preview,
            'from' => $this->from,
            'clickBack' => $this->clickBack
        ];
    }
}