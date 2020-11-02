<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Tags\Store;
use Blog\Http\Requests\Tags\Update;
use Blog\Models\Tag;
use Blog\Services\CheckProfanity;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;

/**
 * Description of CategoryController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class TagController extends Controller
{
    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $translator;

    /**
     * TagController constructor.
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('index', Tag::class);

        return view('blog::pages.tags.index', [
            'records' => Tag::q($request->get('search'))->withCount('posts')->paginate(10),
            'enableSearch' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Tag $tag
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);

        return view('blog::pages.tags.show', [
            'record' => $tag,
            'posts' => $tag->posts()->paginate(10),
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
        $this->authorize('create', Tag::class);

        return view('blog::pages.tags.create', [
            'model' => new Tag(),
            'enableVoice' => true,
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
        $model = new Tag();
        $model->fill($request->all());

        $checkProfanity = new CheckProfanity($model);
        if ($checkProfanity->check()) {
            return redirect()->back()->withInput($request->all());
        }
        $model->save();

        return redirect()
            ->route('blog::tags.index')
            ->with('message', $this->translator->get('blog::flash.saved', ['model' => 'Tag']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);

        return view('blog::pages.tags.edit', [
            'model' => $tag,
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Update $request
     * @param Tag    $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Tag $tag)
    {
        $tag->fill($request->all());

        $checkProfanity = new CheckProfanity($tag);
        if ($checkProfanity->check()) {
            return redirect()->back()->withInput($request->all());
        }

        $tag->save();

        return redirect()->route('blog::tags.index')->with('message', $this->translator->get('blog::flash.updated', ['model' => 'Tag']));
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param \Blog\Models\Tag $tag
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return redirect()->back()->with('message', $this->translator->get('blog::flash.deleted', ['model' => $tag->name]));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Search(Request $request)
    {
        $tags = Tag::query()->q($request->get('term'))->take(10)->get();
        $data = $tags->map(function ($tag) {
            return [
                'id' => $tag->name,
                'text' => $tag->name,
            ];
        })->all();

        return response()->json([
            'results' => $data,
        ]);
    }
}
