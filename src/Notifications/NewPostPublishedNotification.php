<?php

namespace Blog\Notifications;

use Blog\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

/**
 */
class NewPostPublishedNotification extends Notification
{
    use Queueable;
    /**
     * @var \Blog\Models\Post
     */
    protected $post;
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $link;

    /**
     * Create a new notification instance.
     *
     * @param \Blog\Models\Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->subject = 'New Post <b>' . $post->title . '</b> published';
        $this->link = route('blog::frontend.blog.posts.show', $post->slug);
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
        return (new MailMessage())
            ->subject(strip_tags($this->subject))
            ->line('New Post published that you may be interested')
            ->line($this->subject)
            ->action('View', $this->link)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * @return array
     */
    public function toDatabase()
    {
        return [
            'message' => $this->subject,
            'link' => $this->link,
            'icon' => 'fa fa-doc',
        ];
    }

    /**
     * @return \NotificationChannels\WebPush\WebPushMessage
     */
    public function toWebPush()
    {
        return (new WebPushMessage())
            ->title('New Post published that you are interested in.')
            ->body($this->subject)
            ->requireInteraction()
            ->data(['url' => $this->link]);
    }
}
