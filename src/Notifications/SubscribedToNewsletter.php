<?php

namespace Blog\Notifications;

use Blog\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class SubscribedToNewsletter extends Notification
{
    use Queueable;

    /**
     * @var Newsletter
     */
    public $newsletter;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $actionLink;

    /**
     * Create a new notification instance.
     *
     * @param Newsletter $newsletter
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
        $this->subject = $this->newsletter->email . ' subscribed to our newsletter.';
        $this->actionLink = route('blog::newsletters.show', $this->newsletter->id);
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
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->subject($this->subject)
            ->view('blog::emails.notifications.SubscribedToNewsletter', [
                'newsletter' => $this->newsletter,
            ]);
    }

    /**
     * Format message for database notification.
     *
     * @param $notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
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
            ->title('New Subscriber')
            ->body($this->newsletter->email)
            ->requireInteraction()
            ->data(['url' => $this->actionLink]);
    }
}
