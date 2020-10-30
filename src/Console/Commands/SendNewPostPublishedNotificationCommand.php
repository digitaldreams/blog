<?php

namespace Blog\Console\Commands;

use Blog\Notifications\NewPostPublishedNotification;
use Blog\Repositories\PostRepository;
use Illuminate\Console\Command;
use Illuminate\Notifications\ChannelManager;

class SendNewPostPublishedNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:preferred-post-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preferred post published on last 24 hours.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param \Blog\Repositories\PostRepository        $postRepository
     * @param \Illuminate\Notifications\ChannelManager $channelManager
     *
     * @return int
     *
     * @throws \Exception
     */
    public function handle(PostRepository $postRepository, ChannelManager $channelManager)
    {
        $posts = $postRepository->publishedBetween();
        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                $users = $postRepository->findPreferredUsersForPost($post);
                if ($users->count() > 0) {
                    $channelManager->send($users, new NewPostPublishedNotification($post));
                }
                $this->info(sprintf('%s user send post %s ', $users->count(), $post->title));
            }
        }
        $this->info(sprintf('There are %s post published in last 24 hours', $posts->count()));

        return 0;
    }
}
