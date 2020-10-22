<?php

namespace Blog\Http\Controllers\Frontend;

use Blog\Http\Controllers\Controller;
use Blog\Models\Category;
use Blog\Models\Post;
use Blog\Models\Tag;
use Blog\Repositories\CategoryRepository;
use Blog\Repositories\PostRepository;
use Blog\Repositories\TagRepository;
use Blog\Services\BlogHomeService;
use Illuminate\Http\Request;

/**
 * Description of PostController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class PostController extends Controller
{
    /**
     * @var \Blog\Repositories\PostRepository
     */
    protected $postRepository;
    /**
     * @var \Blog\Repositories\TagRepository
     */
    protected $tagRepository;
    /**
     * @var \Blog\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    /**
     * PostController constructor.
     *
     * @param \Blog\Repositories\PostRepository     $postRepository
     * @param \Blog\Repositories\TagRepository      $tagRepository
     * @param \Blog\Repositories\CategoryRepository $categoryRepository
     */
    public function __construct(PostRepository $postRepository, TagRepository $tagRepository, CategoryRepository $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, BlogHomeService $blogHomeService)
    {
        return view('blog::pages.posts.frontend.index', [
            'records' => $blogHomeService->get($request->get('search'), auth()->user(), 6),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Post    $post
     * @param mixed   $category
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $category, Post $post)
    {
        $post->incrementViewCount();

        return view('blog::pages.posts.frontend.show', [
            'record' => $post,
            'relatedPosts' => $this->postRepository->relatedPosts($post),
        ]);
    }

    public function blog(Request $request)
    {
        $fpost = $this->postRepository->featuredPosts(4);
        $latest = $this->postRepository->latestPosts(4);

        return view('blog::pages.posts.frontend.blog', [
            'leadPost' => $fpost->shift(),
            'featuredPosts' => $fpost,
            'latest' => $latest,
            'tags' => $this->tagRepository->popular(),
            'categories' => $this->categoryRepository->popular(),
        ]);
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category(Request $request, Category $category)
    {
        $categorIds = $category->children()->pluck('id')->toArray();
        $categorIds[] = $category->id;
        $posts = Post::where('status', Post::STATUS_PUBLISHED)
            ->whereIn('category_id', $categorIds)
            ->orderBy('created_at', 'desc');

        return view('blog::pages.posts.frontend.index', [
            'records' => $posts->paginate(6),
            'model' => $category,
        ]);
    }

    /**
     * @param $tag
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tag(Request $request, $tag)
    {
        $posts = Post::where('status', Post::STATUS_PUBLISHED)->whereHas('tags', function ($q) use ($tag) {
            $q->where('slug', $tag);
        })->orderBy('created_at', 'desc');
        $model = Tag::where('slug', $tag)->firstOrFail();
        $model->title = $model->name ?? '';

        return view('blog::pages.posts.frontend.index', [
            'records' => $posts->paginate(6),
            'model' => $model,
        ]);
    }

}
