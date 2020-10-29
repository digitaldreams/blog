<?php

namespace Blog\Http\Controllers\Frontend;

use Blog\Http\Controllers\Controller;
use Blog\Models\Category;
use Blog\Models\Post;
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
     * @param \Illuminate\Http\Request       $request
     * @param \Blog\Services\BlogHomeService $blogHomeService
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index(Request $request, BlogHomeService $blogHomeService)
    {
        return view('blog::pages.posts.frontend.index', [
            'keywords' => $this->postRepository->keywords(),
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

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
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
            'keywords' => $this->postRepository->keywords(),
        ]);
    }

    /**
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
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
            'keywords' => $this->postRepository->keywords(),
        ]);
    }
}
