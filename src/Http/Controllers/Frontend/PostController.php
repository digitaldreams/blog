<?php

namespace Blog\Http\Controllers\Frontend;

use Blog\Http\Controllers\Controller;
use Blog\Http\Requests\Posts\Index;
use Blog\Models\Category;
use Blog\Models\Post;
use Blog\Models\Tag;
use Illuminate\Http\Request;

/**
 * Description of PostController.
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
    public function index(Request $request)
    {
        $posts = Post::where('status', Post::STATUS_PUBLISHED)->q($request->get('search'))
            ->with(['category', 'user'])->withCount('comments');

        return view('blog::pages.posts.frontend.index', [
            'records' => $posts->latest()->paginate(6),
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
            'relatedPosts' => Post::where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->orderBy('total_view', 'desc')
                ->latest()
                ->limit(3)
                ->get(),
        ]);
    }

    public function bloghome(Request $request)
    {
        $fpost = Post::where('status', Post::STATUS_PUBLISHED)
            ->where('is_featured', Post::IS_FEATURED)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $latest = Post::where('status', Post::STATUS_PUBLISHED)
            ->where('is_featured', 0)->orderBy('created_at', 'desc')
            ->take(6)->get();

        return view('blog::pages.bloghome', [
            'leadPost' => $fpost->shift(),
            'featuredPosts' => $fpost,
            'latest' => $latest,
            'tags' => Tag::withCount('posts')->get(),
            'categories' => Category::whereNull('parent_id')->take(10)->get(),
        ]);
    }

    /**
     * @param Index    $request
     * @param Category $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category(Request $request, Category $category)
    {
        $posts = Post::where('status', Post::STATUS_PUBLISHED)
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc');

        return view('blog::pages.posts.frontend.index', [
            'records' => $posts->paginate(6),
            'model' => $category,
        ]);
    }

    /**
     * @param Index $request
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

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function smartSearch(Request $request)
    {
        $result = [];
        $search = $request->get('search');

        $posts = Post::search($search)->select(['id', 'title', 'category_id', 'slug'])->take(10)->get();

        foreach ($posts as $post) {
            $result[] = [
                'title' => $post->title,
                'link' => route('blog::frontend.blog.posts.show', ['category' => $post->category->slug, 'post' => $post->slug]),
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $result,
        ]);
    }
}
