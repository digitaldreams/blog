<?php
Route::group(['middleware' => ['web'], 'namespace' => 'Blog\Http\Controllers', 'as' => 'blog::'], function () {
    Route::get('blog', 'Frontend\PostController@bloghome')->name('posts.home');
    Route::get('blog/tags/{tag}', 'Frontend\PostController@tag')->name('frontend.blog.tags.index');

    Route::get('blog/{category}/{post}', 'Frontend\PostController@show')->name('frontend.blog.posts.show');
    Route::get('blog/{category}', 'Frontend\PostController@category')->name('frontend.blog.categories.index');
    Route::get('posts/smart-search', 'Frontend\PostController@smartSearch')->name('frontend.blog.smartSearch');
    Route::post('newsletter/subscribe', 'Frontend\NewsletterController@subscribe')->name('frontend.blog.newsletters.subscribe');
    Route::get('newsletter/unsubscribe', 'Frontend\NewsletterController@unsubscribe')->name('frontend.blog.newsletters.unsubscribe');
    Route::group(['prefix' => 'app', 'middleware' => 'auth'], function () {
        Route::post('posts/{post}/status/{status}','PostController@status')->name('posts.status');
        Route::resource('posts', 'PostController');
        Route::resource('posts.comments', 'CommentController');
        Route::resource('categories', 'CategoryController');
        Route::resource('tags', 'TagController');
        Route::get('activities/{action}', 'ActivityController@show')->name('activities.show');
        Route::resource('activities', 'ActivityController')->only(['store', 'update', 'destroy']);
        Route::resource('types', 'ActivityTypeController');
        Route::resource('newsletters', 'NewsletterController', ['only' => ['index', 'show']]);
    });

});