<?php

return [
    /*
     * User model are used in Authentication
     */
    'userModel' => App\Models\User::class,

    /*
     * Layout's will be used to
     */
    'layout' => [
        'show' => 'blog::layouts.app',
        'create' => 'blog::layouts.app',
    ],
    'activityType' => [
        //keyword=>Full Namespace Model class
    ],
    'defaultPhoto' => '/storage/images/default.png',

    /*
     * Configure the Policies. To override this classes. Simply extends this classes.
     */
    'policies' => [
        \Blog\Models\Post::class => \Blog\Policies\PostPolicy::class,
        \Blog\Models\Comment::class => \Blog\Policies\CommentPolicy::class,
        \Blog\Models\Category::class => \Blog\Policies\CategoryPolicy::class,
        \Blog\Policies\TagPolicy::class => \Blog\Policies\TagPolicy::class,
        \Blog\Models\Newsletter::class => \Blog\Policies\NewsletterPolicy::class,
    ],
];
