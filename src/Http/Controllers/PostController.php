<?php

namespace Blog\Http\Controllers;

use App\Models\User;
use Blog\Http\Requests\Posts\Store;
use Blog\Http\Requests\Posts\Update;
use Blog\Jobs\TableOfContentGeneratorJob;
use Blog\Models\Post;
use Blog\Notifications\NewPostApproval;
use Blog\Notifications\NewPostApprovalCompleted;
use Blog\Services\CheckProfanity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Photo\Models\Photo;
use Photo\Services\PhotoService;

/**
 * Description of PostController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('index', Post::class);

        $posts = Post::search($request->get('search'))
            ->with(['category', 'user'])
            ->withCount('comments');

        return view('blog::pages.posts.index', [
            'records' => $posts->latest()->paginate(6),
            'enableSearch' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        return view('blog::pages.posts.show', [
            'record' => $post,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        $model = new Post();
        return view('blog::pages.posts.create', [
            'model' => $model,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Store $request)
    {
        $this->authorize('create', Post::class);

        $model = new Post;
        $model->fill($request->except(['body']));
        $model->body = $request->get('body');
        if ($request->hasFile('image')) {
            $model->setImageSize();
            $photo = new Photo();
            $photo->caption = $request->get('title');
            $model->image_id = (new PhotoService($photo))->setFolder('posts')->save($request, 'image')->id;
        }

        $checkProfinity = new CheckProfanity($model);

        if ($checkProfinity->check()) {
            return redirect()->back()->withInput($request->all());
        }

        if ($model->save()) {
            dispatch(new TableOfContentGeneratorJob($model));

            if (!auth()->user()->can('approve', Post::class)) {
                //Notify to Admin
                Notification::send(User::getAdmins(), new NewPostApproval($model));
                session()->flash('message', 'Post saved successfully and one of our moderator will review it soon');
            } else {
                session()->flash('message', 'Post saved successfully');
            }
            $model->tags()->sync($request->get('tags', []));

            return redirect()->route('blog::posts.index');
        } else {
            session()->flash('message', 'Oops something went wrong while saving your post');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('blog::pages.posts.edit', [
            'model' => $post,
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Update $request
     * @param Post   $post
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Update $request, Post $post)
    {
        $this->authorize('update', $post);
        $post->fill($request->all());

        if ($request->hasFile('image')) {
            $post->setImageSize();
            $photo = new Photo();
            $photo->caption = $request->get('title');
            $photo->title = $request->get('title');
            $post->image_id = (new PhotoService($photo))->setFolder('posts')->save($request, 'image')->id;
        }
        $checkProfinity = new CheckProfanity($post);

        if ($checkProfinity->check()) {
            return redirect()->back()->withInput($request->all());
        }

        if ($post->save()) {
            dispatch(new TableOfContentGeneratorJob($post));

            $post->tags()->sync($request->get('tags', []));
            session()->flash('message', 'Post successfully updated');
            return redirect()->route('blog::posts.index');
        } else {
            session()->flash('error', 'Oops something went wrong while updating Post');
        }
        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Destroy $request
     * @param Post    $post
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->delete()) {
            session()->flash('message', 'Post successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Post');
        }
    }

    /**
     * Approve or Deny a Post.
     *
     * @param \Blog\Models\Post                 $post
     * @param                                   $status
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function status(Post $post, $status)
    {
        $this->authorize('approve', $post);

        $post->status = $status;
        $post->save();
        if (is_object($post->user)) {
            $post->user->notify(new NewPostApprovalCompleted($post));
        }
        return redirect()->back()->with('message', 'Thanks for your action');
    }

}
