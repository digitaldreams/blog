<?php

namespace Blog\Mail;

use Blog\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Collection
     */
    public $posts;
    /**
     * @var Newsletter
     */
    public $newsletter;
    public $unsubscribeLink;

    /**
     * Create a new message instance.
     *
     * @param Collection $posts
     * @param Newsletter $newsletter
     */
    public function __construct(Collection $posts, Newsletter $newsletter)
    {
        $this->posts = $posts;
        $this->newsletter = $newsletter;
        $this->unsubscribeLink = route('blog::frontend.blog.newsletters.unsubscribe', ['email' => $newsletter->email]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your weekly newsletter from The Bom')->view('blog::emails.weekly_newsletter');
    }
}
