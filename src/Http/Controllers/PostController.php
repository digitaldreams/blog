<?php

namespace Blog\Http\Controllers;

use App\Models\User;
use Blog\Http\Requests\Posts\Create;
use Blog\Http\Requests\Posts\Destroy;
use Blog\Http\Requests\Posts\Edit;
use Blog\Http\Requests\Posts\Index;
use Blog\Http\Requests\Posts\Store;
use Blog\Http\Requests\Posts\Update;
use Blog\Jobs\TableOfContentGeneratorJob;
use Blog\Models\Post;
use Blog\Notifications\NewPostApproval;
use Blog\Notifications\NewPostApprovalCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Photo\Models\Photo;
use Photo\Services\PhotoService;
use SEO\Seo;

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
     * @param Index $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
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
        $post->incrementViewCount();
        return view('blog::pages.posts.show', [
            'record' => $post,
            'relatedPosts' => Post::where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->orderBy('total_view', 'desc')
                ->limit(3)
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Create $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {
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
        $model = new Post;
        $model->fill($request->except(['body']));
        $model->body = $request->get('body');
        if ($request->hasFile('image')) {
            $model->setImageSize();
            $photo = new Photo();
            $photo->caption = $request->get('title');
            $model->image_id = (new PhotoService($photo))->setFolder('posts')->save($request, 'image')->id;
        }
        if ($model->save()) {
            if (!auth()->user()->can('approve', Post::class)) {
                //Notify to Admin
                Notification::send(User::getAdmins(), new NewPostApproval($model));
                session()->flash('app_message', 'Post saved successfully and one of our moderator will review it soon');
            } else {
                session()->flash('app_message', 'Post saved successfully');
            }
            dispatch(new TableOfContentGeneratorJob($model));
            $model->tags()->sync($request->get('tags', []));
            Seo::save($model, route('blog::frontend.blog.posts.show', [
                'category' => $model->category->slug,
                'post' => $model->slug,
            ]), [
                'title' => $model->title,
                'images' => [
                    $model->getImageUrl(),
                ],
            ]);

            return redirect()->route('blog::posts.index');
        } else {
            session()->flash('app_message', 'Oops something went wrong while saving your post');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Edit $request
     * @param Post $post
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, Post $post)
    {
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
        $post->fill($request->all());

        if ($request->hasFile('image')) {
            $post->setImageSize();
            $photo = new Photo();
            $photo->caption = $request->get('title');
            $photo->title = $request->get('title');
            $post->image_id = (new PhotoService($photo))->setFolder('posts')->save($request, 'image')->id;
        }

        if ($post->save()) {
            dispatch(new TableOfContentGeneratorJob($post));
            $post->tags()->sync($request->get('tags', []));
            Seo::save($post, route('blog::frontend.blog.posts.show', [
                'category' => $post->category->slug,
                'post' => $post->slug,
            ]), [
                'title' => $post->title,
                'images' => [
                    $post->getImageUrl(),
                ],
            ]);

            session()->flash('app_message', 'Post successfully updated');
            return redirect()->route('blog::posts.index');
        } else {
            session()->flash('app_error', 'Oops something went wrong while updating Post');
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
    public function destroy(Destroy $request, Post $post)
    {
        if ($post->delete()) {
            session()->flash('app_message', 'Post successfully deleted');
        } else {
            session()->flash('app_error', 'Error occurred while deleting Post');
        }

        return redirect()->back();
    }

    /**
     * @param \Blog\Http\Requests\Posts\Destroy $request
     * @param \Blog\Models\Post                 $post
     * @param                                   $status
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status(Destroy $request, Post $post, $status)
    {
        $post->status = $status;
        $post->save();
        if (is_object($post->user)) {
            $post->user->notify(new NewPostApprovalCompleted($post));
        }
        return redirect()->back()->with('app_message', 'Thanks for your action');
    }

}
