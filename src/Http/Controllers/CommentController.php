<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Comments\Destroy;
use Blog\Http\Requests\Comments\Index;
use Blog\Http\Requests\Comments\Store;
use Blog\Models\Comment;
use Blog\Models\Post;
use Blog\Notifications\CommentNotification;
use Notification;
use Permit\Models\User;

/**
 * Description of CommentController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request, Post $post)
    {
        return view('blog::pages.comments.index', ['records' => Comment::paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Store $request
     * @param Post $post
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Post $post)
    {
        $model = new Comment;
        $model->fill($request->all());
        $model->post_id = $post->id;
        $model->user_id = auth()->user()->id;
        if ($model->save()) {
            Notification::send(User::superAdmin()->get(), new CommentNotification($post, auth()->user()));
            session()->flash('app_message', 'Comment saved successfully');
            return redirect()->route('blog::posts.show', $post->slug);
        } else {
            session()->flash('app_message', 'Something is wrong while saving Comment');
        }
        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy $request
     * @param Post $post
     * @param  Comment $comment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, Post $post, Comment $comment)
    {
        if ($comment->delete()) {
            session()->flash('app_message', 'Comment successfully deleted');
        } else {
            session()->flash('app_error', 'Error occurred while deleting Comment');
        }
        return redirect()->back();
    }
}
