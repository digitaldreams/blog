<?php
Route::group(['middleware' => ['web'], 'namespace' => 'Blog\Http\Controllers', 'as' => 'blog::'], function () {
    Route::get('blog.html', 'Frontend\PostController@bloghome')->name('posts.home');
    Route::get('blog/tags/{tag}.html', 'Frontend\PostController@tag')->name('frontend.blog.tags.index');

    Route::get('blog/{category}/{post}.html', 'Frontend\PostController@show')->name('frontend.blog.posts.show');
    Route::get('blog/{category}.html', 'Frontend\PostController@category')->name('frontend.blog.categories.index');
    Route::get('posts/smart-search', 'Frontend\PostController@smartSearch')->name('frontend.blog.smartSearch');
    Route::post('newsletter/subscribe', 'Frontend\NewsletterController@subscribe')->name('frontend.blog.newsletters.subscribe');
    Route::get('newsletter/unsubscribe', 'Frontend\NewsletterController@unsubscribe')->name('frontend.blog.newsletters.unsubscribe');
    Route::group(['prefix' => 'app', 'middleware' => 'auth'], function () {
        Route::get('preferences', 'PreferenceController@index')->name('preferences.index');
        Route::post('preferences', 'PreferenceController@store')->name('preferences.store');
        Route::post('posts/{post}/status/{status}', 'PostController@status')->name('posts.status');
        Route::resource('posts', 'PostController');
        Route::resource('posts.comments', 'CommentController');
        Route::get('categories/select2', 'CategoryController@select2Search')->name('categories.select2');
        Route::resource('categories', 'CategoryController');
        Route::get('tags/select2', 'TagController@select2Search')->name('tags.select2');
        Route::resource('tags', 'TagController');
        Route::get('activities/{action}', 'ActivityController@show')->name('activities.show');
        Route::resource('activities', 'ActivityController')->only(['store', 'update', 'destroy']);
        Route::resource('types', 'ActivityTypeController');
        Route::resource('newsletters', 'NewsletterController', ['only' => ['index', 'show']]);
    });

});
