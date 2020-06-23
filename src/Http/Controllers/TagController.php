<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Tags\Create;
use Blog\Http\Requests\Tags\Destroy;
use Blog\Http\Requests\Tags\Index;
use Blog\Http\Requests\Tags\Show;
use Blog\Http\Requests\Tags\Store;
use Blog\Http\Requests\Tags\Update;
use Blog\Models\Category;
use Blog\Models\Tag;
use Illuminate\Http\Request;

/**
 * Description of CategoryController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Index $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('blog::pages.tags.index', [
            'records' => Tag::q($request->get('search'))->withCount('posts')->paginate(10),
            'enableSearch' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Show $request
     * @param Tag  $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Tag $tag)
    {
        return view('blog::pages.tags.show', [
            'record' => $tag,
            'posts' => $tag->posts()->paginate(10),
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
        return view('blog::pages.tags.create', [
            'model' => new Tag,
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
        $model = new Tag;
        $model->fill($request->all());

        if ($model->save()) {

            session()->flash('app_message', 'Tag saved successfully');
            return redirect()->route('blog::tags.index');
        } else {
            session()->flash('app_message', 'Oops something went wrong while saving your tag');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Tag     $tag
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Tag $tag)
    {
        return view('blog::pages.tags.edit', [
            'model' => $tag,
            'enableVoice' => true,
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
        if ($tag->save()) {
            session()->flash('app_message', 'Tag successfully updated');
            return redirect()->route('blog::tags.index');
        } else {
            session()->flash('app_error', 'Oops something went wrong while updating tag');
        }
        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Destroy  $request
     * @param Category $category
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, Tag $tag)
    {
        if ($tag->delete()) {
            session()->flash('app_message', 'Tag successfully deleted');
        } else {
            session()->flash('app_error', 'Error occurred while deleting Tag');
        }
        return redirect()->back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select2Search(Request $request)
    {
        $tags = Tag::q($request->get('term'))->take(10)->get();
        $data = $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'text' => $tag->name,
            ];
        })->all();
        return response()->json([
            'results' => $data,
        ]);
    }
}
