<?php

namespace Blog\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $actor;

    /**
     * @var string
     */
    private $subject;

    private $actionLink;

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
        $this->subject = $this->actor->name . ' make a comment on ' . $this->model->title;
        $this->actionLink = route('blog::frontend.blog.posts.show', [
            'category' => $this->model->category->slug,
            'post' => $this->model->slug,
        ]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail', WebPushChannel::class];
    }

    /**
     * @param $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->subject($this->subject)->view('blog::emails.notifications.comment', [
            'post' => $this->model,
        ]);
    }

    public function toDatabase()
    {
        return [
            'message' => $this->subject,
            'link' => $this->actionLink,
            'icon' => 'fa fa-user-plus',
        ];
    }

    /**
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush()
    {
        return (new WebPushMessage())
            ->title($this->subject)
            ->body($this->model->title)
            ->requireInteraction()
            ->data(['url' => $this->actionLink]);
    }
}
