<?php

namespace Blog\Notifications;

use Blog\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewPostApprovalCompleted extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Post
     */
    public $post;

    /**
     * @var
     */
    private $subject;

    /**
     * @var string
     */
    protected $actionLink;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->subject = sprintf('Your post %s is %s', $this->post->title, $this->post->status);
        $this->actionLink = route('blog::posts.show', $this->post->slug);
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
        return ['mail', 'database', WebPushChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->subject($this->subject)
            ->view('blog::emails.notifications.NewPostApprovalCompleted', [
                'post' => $this->post,
            ]);
    }

    /**
     * @return array
     */
    public function toDatabase()
    {
        return [
            'message' => $this->subject,
            'link' => $this->actionLink,
            'icon' => 'fa fa-doc',
        ];
    }

    /**
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush()
    {
        return (new WebPushMessage())
            ->title($this->subject)
            ->body($this->post->title)
            ->requireInteraction()
            ->data(['url' => $this->actionLink]);
    }
}
