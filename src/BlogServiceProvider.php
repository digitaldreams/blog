<?php

namespace Blog;

use Blog\Models\Activity;
use Blog\Models\ActivityType;
use Blog\Models\Category;
use Blog\Models\Comment;
use Blog\Models\Newsletter;
use Blog\Models\Post;
use Blog\Models\Tag;
use Blog\Observers\ActivityObserver;
use Blog\Observers\CategoryObserver;
use Blog\Observers\PostObserver;
use Blog\Observers\TagObserver;
use Blog\Policies\ActivityPolicy;
use Blog\Policies\ActivityTypePolicy;
use Blog\Policies\CategoryPolicy;
use Blog\Policies\CommentPolicy;
use Blog\Policies\NewsletterPolicy;
use Blog\Policies\PostPolicy;
use Blog\Policies\TagPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The policy.
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
        ActivityType::class => ActivityTypePolicy::class,
        Newsletter::class => NewsletterPolicy::class,
    ];

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    /**
     * BlogServiceProvider constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->router = $app->get('router');
    }

    /**
     * Register Important services that will be used in application.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'blog');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'blog');
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        $this->registerPolicies();
        $this->registerListeners();

        Post::observe(PostObserver::class);
        Category::observe(CategoryObserver::class);
        Tag::observe(TagObserver::class);
        Activity::observe(ActivityObserver::class);
    }

    /**
     * Register less important services to application.
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/blog.php' => config_path('blog.php'),
            ], 'blog-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/blog'),
            ], 'blog-view');

            $this->publishes([
                __DIR__ . '/../resources/assets' => public_path('blog'),
            ], 'blog-assets');

            $this->publishes([
                __DIR__ . '/../resources/plugins' => resource_path('js'),
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
        $configuredPolicies = config('blog.policies');

        foreach ($this->policies as $key => $value) {
            if (isset($configuredPolicies[$key]) && class_exists($configuredPolicies[$key])) {
                Gate::policy($key, $configuredPolicies[$key]);
            } else {
                Gate::policy($key, $value);
            }
        }
    }

    /**
     * Register Listeners.
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
