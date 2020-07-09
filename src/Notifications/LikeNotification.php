<?php

namespace Blog\Notifications;

use Blog\Models\Category;
use Blog\Models\Post;
use Blog\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification
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
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
    public function toDatabase(): array
    {
        $data = $this->getData();

        return [
            'message' => $this->actor->name . ' likes ' . $data['message'] ?? '',
            'link' => $data['link'] ?? '',
            'icon' => 'fa fa-thumbs-up',
        ];
    }

    public function getData()
    {
        switch (get_class($this->model)) {
            case Post::class:
                return [
                    'message' => $this->model->title,
                    'link' => route('blog::posts.show', $this->model->slug),
                ];
                break;
            case Category::class:
                return [
                    'message' => $this->model->title,
                    'link' => route('frontend.blog.categories.index', $this->model->slug),
                ];
                break;
            case Tag::class:
                return [
                    'message' => $this->model->name,
                    'link' => route('frontend.blog.tags.index', $this->model->slug),
                ];
                break;
        }
    }
}
