<?php
Route::group(['middleware' => ['web'],'as'=>'blog::', 'namespace' => 'Blog\Http\Controllers'], function () {

    Route::resource('posts', 'PostController');

    Route::resource('posts.comments', 'CommentController');

    Route::resource('categories', 'CategoryController');
});