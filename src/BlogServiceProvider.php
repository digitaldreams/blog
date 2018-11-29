<?php

namespace Blog;

use Blog\Models\Category;
use Blog\Models\Comment;
use Blog\Models\Post;
use Blog\Models\Tag;
use Blog\Policies\CategoryPolicy;
use Blog\Policies\CommentPolicy;
use Blog\Policies\PostPolicy;
use Blog\Policies\TagPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Blog\Models\Activity;
use Blog\Policies\ActivityPolicy;
use Blog\Models\ActivityType;
use Blog\Policies\ActivityTypePolicy;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * The policy
     *
     * @var array
     */
    protected $policies = [
        //'Permit\Model' => 'Permit\Policies\ModelPolicy',
        Category::class => CategoryPolicy::class,
        Post::class => PostPolicy::class,
        Tag::class => TagPolicy::class,
        Comment::class => CommentPolicy::class,
        Activity::class => ActivityPolicy::class,
        ActivityType::class => ActivityTypePolicy::class
    ];

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];

    /**
     * Register Important services that will be used in application
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'blog');
         $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->registerPolicies();
        $this->registerListeners();
    }

    /**
     * Register less important services to application
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
            $this->publishes([
                __DIR__ . '/../config/blog.php' => config_path('blog.php')
            ], 'blog-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/blog'),
            ], 'blog-view');

            $this->publishes([
                __DIR__ . '/../resources/assets' => public_path('blog')
            ], 'blog-assets');

            $this->mergeConfigFrom(
                __DIR__ . '/../config/blog.php', 'blog'
            );
        }

    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register Listeners
     */
    public function registerListeners()
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}