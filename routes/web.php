<?php
$this->router->group(['middleware' => ['web'], 'namespace' => 'Blog\Http\Controllers', 'as' => 'blog::'], function () {
    $this->router->get('blog.html', 'Frontend\PostController@blog')->name('posts.home');
    $this->router->get('blog/posts.html', 'Frontend\PostController@index')->name('frontend.blog.posts.index');

    $this->router->get('blog/{category}/{post}.html', 'Frontend\PostController@show')->name('frontend.blog.posts.show');
    $this->router->get('blog/{category}.html', 'Frontend\PostController@category')->name('frontend.blog.categories.index');

    $this->router->post('newsletter/subscribe', 'Frontend\NewsletterController@subscribe')->name('frontend.blog.newsletters.subscribe');
    $this->router->get('newsletter/unsubscribe', 'Frontend\NewsletterController@unsubscribe')->name('frontend.blog.newsletters.unsubscribe');

    $this->router->group(['prefix' => 'app', 'middleware' => 'auth'], function () {
        $this->router->get('preferences', 'PreferenceController@index')->name('preferences.index');
        $this->router->post('preferences', 'PreferenceController@store')->name('preferences.store');

        $this->router->post('posts/{post}/status/{status}', 'PostController@status')->name('posts.status');
        $this->router->resource('posts', 'PostController');
        $this->router->resource('posts.comments', 'CommentController');

        $this->router->get('categories/select2', 'CategoryController@select2Search')->name('categories.select2');
        $this->router->resource('categories', 'CategoryController');

        $this->router->get('tags/select2', 'TagController@select2Search')->name('tags.select2');
        $this->router->resource('tags', 'TagController');

        $this->router->get('activities/{action}', 'ActivityController@show')->name('activities.show');
        $this->router->resource('activities', 'ActivityController')->only(['store', 'update', 'destroy']);

        $this->router->resource('types', 'ActivityTypeController');
        $this->router->resource('newsletters', 'NewsletterController', ['only' => ['index', 'show']]);
    });

});
