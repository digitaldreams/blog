<?php

namespace Blog\Http\Controllers;

use App\Models\User;
use Blog\Http\Requests\Comments\Destroy;
use Blog\Http\Requests\Comments\Index;
use Blog\Http\Requests\Comments\Store;
use Blog\Models\Comment;
use Blog\Models\Post;
use Blog\Notifications\CommentNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Translation\Translator;

/**
 * Description of CommentController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class CommentController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * CommentController constructor.
     *
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->middleware('auth', ['except' => 'index']);
        $this->translator = $translator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     * @param Post  $post
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request, Post $post)
    {
        return view('blog::pages.comments.index', ['records' => Comment::paginate(10)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     * @param Post  $post
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request, Post $post)
    {
        $model = new Comment();
        $model->fill($request->all());
        $model->post_id = $post->id;
        $model->user_id = auth()->user()->id;
        $model->save();

        Notification::send(User::getAdmins(), new CommentNotification($post, auth()->user()));

        return redirect()
            ->back()
            ->with('message', $this->translator->get('blog::flash.saved', ['model' => 'Comment']));
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Destroy $request
     * @param Post    $post
     * @param Comment $comment
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Destroy $request, Post $post, Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('message', $this->translator->get('blog::flash.deleted', ['model' => 'Comment']));
    }
}
