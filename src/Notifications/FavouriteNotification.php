<?php

namespace Blog\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Permit\Notifications\Channels\Model as ModelChannel;

class FavouriteNotification extends Notification
{
    use Queueable;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var Model
     */
    public $actor;

    /**
     * Create a new notification instance.
     *
     * @param Model $model
     * @param Model $user
     */
    public function __construct(Model $model, Model $user)
    {
        $this->model = $model;
        $this->actor = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ModelChannel::class];
    }

    public function toModel($notifiable)
    {
        return [
            'model' => $this->model,
            'actor' => $this->actor,
            'verb' => 'mark as favourite'
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
