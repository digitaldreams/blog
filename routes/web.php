<?php
Route::group(['middleware' => ['web'], 'namespace' => 'Blog\Http\Controllers', 'prefix' => 'blog', 'as' => 'blog::'], function () {

    Route::resource('posts', 'PostController');
    Route::resource('posts.comments', 'CommentController');
    Route::resource('categories', 'CategoryController');
    Route::resource('tags', 'TagController');
    Route::resource('activities', 'ActivityController')->only(['store', 'update', 'destroy']);
    Route::resource('types', 'ActivityTypeController');
});