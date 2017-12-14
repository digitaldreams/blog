<?php

Route::resource('posts', 'PostController');

Route::resource('posts.comments', 'CommentController');

Route::resource('categories', 'CategoryController');