<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Posts\Store;
use Blog\Http\Requests\Posts\Update;
use Blog\Models\Post;
use Blog\Notifications\NewPostApprovalCompleted;
use Blog\Repositories\PostRepository;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

/**
 * Description of PostController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class PostController extends Controller
{
    /**
     * @var
     */
    protected PostRepository $postRepository;
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * PostController constructor.
     *
     * @param \Blog\Repositories\PostRepository  $postRepository
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(PostRepository $postRepository, Translator $translator)
    {
        $this->postRepository = $postRepository;
        $this->translator = $translator;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('index', Post::class);

        $search = $request->get('search');
        if (!empty($search)) {
            $posts = $this->postRepository->search($search, 8);
        } else {
            $posts = Post::query()->latest()->paginate(8);
        }

        return view('blog::pages.posts.index', [
            'records' => $posts,
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
     *
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
     *
     * @throws \Exception
     */
    public function store(Store $request)
    {
        $this->authorize('create', Post::class);

        $post = $this->postRepository->create($request->except(['data']), $request->file('image'));

        if (!$post) {
            return redirect()->back()->withInput($request->all());
        }

        return redirect()->route('blog::frontend.blog.posts.show', $post->slug);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     *
     * @return \Illuminate\Http\Response
     *
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
     *
     * @throws \Exception
     */
    public function update(Update $request, Post $post)
    {
        $this->authorize('update', $post);

        $post = $this->postRepository->update($request->except(['data']), $post, $request->file('image'));

        if (!$post) {
            return redirect()->back()->withInput($request->all());
        }

        return redirect()->route('blog::frontend.blog.posts.show', $post->slug);
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Post $post
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->postRepository->delete($post);

        return redirect()
            ->route('blog::posts.index')
            ->with('message', $this->translator->get('blog::flash.deleted', ['model' => $post->title]));
    }

    /**
     * Approve or Deny a Post.
     *
     * @param \Blog\Models\Post $post
     * @param                   $status
     *
     * @return \Illuminate\Http\RedirectResponse
     *
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

        return redirect()->back()->with('message', $this->translator->get('blog::flash.statusChanged', ['status' => $status]));
    }
}
