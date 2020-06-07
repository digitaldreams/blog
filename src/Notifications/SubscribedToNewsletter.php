<?php

namespace Blog\Notifications;

use Blog\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        return ['database', 'mail'];
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
     * Format message for database notification
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
}
