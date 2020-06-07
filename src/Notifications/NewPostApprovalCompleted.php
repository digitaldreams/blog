<?php

namespace Blog\Notifications;

use Blog\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPostApprovalCompleted extends Notification
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
        $this->subject = 'Your post ' . $this->post->title . ' is ' . $this->post->status;
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
        return ['mail', 'database'];
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
            'link' => $this->actionLink,
            'icon' => 'fa fa-doc',
        ];
    }
}
