<?php

namespace App\Notifications;

use App\Client;
use CityNexus\DataStore\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DataProcessed extends Notification
{
    use Queueable;

    private $upload;
    private $client_id;
    private $dataset;
    private $click_back;

    /**
     * Create a new notification instance.
     *
     * @param $upload
     */
    public function __construct($upload)
    {
        $this->upload = $upload;
        $this->dataset = $upload->uploader->dataset->name;
        $this->client_id = config('client.id');
        $this->click_back = Client::find($this->client_id)->domain . '/' . route('upload.show', [$upload->id]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
                    ->line('Your data upload has finished')
                    ->action('View upload report', $this->click_back)
                    ->line('Thank you for using our application!');
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
            'name' => $this->dataset,
            'dataCount' => $this->upload->size,
            'newProperties' => count($this->upload->new_property_ids),
            'client_id' => $this->client_id,
            'clickBack' => $this->click_back
        ];
    }
}
