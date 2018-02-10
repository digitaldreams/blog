<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Posts\Create;
use Blog\Http\Requests\Posts\Destroy;
use Blog\Http\Requests\Posts\Edit;
use Blog\Http\Requests\Posts\Index;
use Blog\Http\Requests\Posts\Store;
use Blog\Http\Requests\Posts\Update;
use Blog\Models\Category;
use Blog\Models\Post;
use Illuminate\Http\Request;

/**
 * Description of PostController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('blog::pages.posts.index', [
            'records' => Post::q($request->get('q'))->with(['category', 'user'])->withCount('comments')->paginate(6),
            'enableSearch' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Post $post)
    {
        $post->incrementViewCount();
        return view('blog::pages.posts.show', [
            'record' => $post,
            'relatedPosts'=> Post::where('category_id',$post->category_id)->where('id','!=',$post->id)->orderBy('total_view','desc')->limit(3)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Create $request
     * @return \Illuminate\Http\Response
     */
    public function create(Create $request)
    {
        return view('blog::pages.posts.create', [
            'model' => new Post,
            'categories' => Category::all(['id', 'title'])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Store $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $model = new Post;
        $model->fill($request->all());

        if ($request->hasFile('image')) {
            $model->image = $request->file('image')->store('images', 'public');
        }
        if ($model->save()) {

            session()->flash('app_message', 'Post saved successfully');
            return redirect()->route('blog::posts.index');
        } else {
            session()->flash('app_message', 'Something is wrong while saving Post');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Edit $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, Post $post)
    {
        return view('blog::pages.posts.edit', [
            'model' => $post,
            'categories' => Category::all(['id', 'title'])
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param  Update $request
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Post $post)
    {
        $post->fill($request->all());

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        if ($post->save()) {

            session()->flash('app_message', 'Post successfully updated');
            return redirect()->route('blog::posts.index');
        } else {
            session()->flash('app_error', 'Something is wrong while updating Post');
        }
        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy $request
     * @param  Post $post
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

}
