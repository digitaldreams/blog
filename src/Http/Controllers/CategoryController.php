<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Categories\Store;
use Blog\Http\Requests\Categories\Update;
use Blog\Models\Category;
use Blog\Services\CheckProfanity;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

/**
 * Description of CategoryController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class CategoryController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * CategoryController constructor.
     *
     * @param \Illuminate\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('index', Category::class);

        return view('blog::pages.categories.index', [
            'records' => Category::query()
                ->search($request->get('search'))
                ->withCount('posts')
                ->with('parentCategory')
                ->paginate(10),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return view('blog::pages.categories.show', [
            'record' => $category,
            'posts' => $category->posts()->paginate(6),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        return view('blog::pages.categories.create', [
            'model' => new Category(),
            'categories' => Category::with('children')->parent()->get(['id', 'title']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Store $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model = new Category();
        $model->fill($request->all());

        $checkProfanity = new CheckProfanity($model);
        if ($checkProfanity->check()) {
            return redirect()->back()->withInput($request->all());
        }

        $model->save();

        return redirect()
            ->route('blog::categories.index')
            ->with('message', $this->translator->get('blog::flash.created', ['model' => 'category']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('blog::pages.categories.edit', [
            'model' => $category,
            'categories' => Category::with('children')->parent()->get(['id', 'title']),
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Update   $request
     * @param Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Category $category)
    {
        $category->fill($request->all());

        $checkProfanity = new CheckProfanity($category);
        if ($checkProfanity->check()) {
            return redirect()->back()->withInput($request->all());
        }

        $category->save();

        return redirect()
            ->route('blog::categories.index')
            ->with('message', $this->translator->get('blog::flash.updated', ['model' => 'Category']));
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Category $category
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('blog::categories.index')
            ->with('message', $this->translator->get('blog::flash.deleted', ['model' => $category->title]));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Search(Request $request)
    {
        $tags = Category::search($request->get('term'))->take(10)->get();
        $data = $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'text' => $tag->title,
            ];
        })->all();

        return response()->json([
            'results' => $data,
        ]);
    }
}
