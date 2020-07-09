<?php

namespace Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Blog\Http\Requests\Newsletters\Create;
use Blog\Http\Requests\Newsletters\Destroy;
use Blog\Http\Requests\Newsletters\Edit;
use Blog\Http\Requests\Newsletters\Index;
use Blog\Http\Requests\Newsletters\Show;
use Blog\Http\Requests\Newsletters\Store;
use Blog\Http\Requests\Newsletters\Update;
use Blog\Models\Newsletter;

/**
 * Description of NewsletterController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class NewsletterController extends Controller
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
        return view('blog::pages.newsletters.index', ['records' => Newsletter::paginate(10)]);
    }

    /**
     * Display the specified resource.
     *
     * @param Show       $request
     * @param Newsletter $newsletter
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, Newsletter $newsletter)
    {
        return view('blog::pages.newsletters.show', [
            'record' => $newsletter,
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
        return view('blog::pages.newsletters.create', [
            'model' => new Newsletter(),
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
        $model = new Newsletter();
        $model->fill($request->all());

        if ($model->save()) {
            session()->flash('message', 'Newsletter saved successfully');

            return redirect()->route('blog::newsletters.index');
        } else {
            session()->flash('message', 'Something is wrong while saving Newsletter');
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Edit       $request
     * @param Newsletter $newsletter
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Edit $request, Newsletter $newsletter)
    {
        return view('blog::pages.newsletters.edit', [
            'model' => $newsletter,
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Update     $request
     * @param Newsletter $newsletter
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Newsletter $newsletter)
    {
        $newsletter->fill($request->all());

        if ($newsletter->save()) {
            session()->flash('message', 'Newsletter successfully updated');

            return redirect()->route('blog::newsletters.index');
        } else {
            session()->flash('error', 'Something is wrong while updating Newsletter');
        }

        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Destroy    $request
     * @param Newsletter $newsletter
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Destroy $request, Newsletter $newsletter)
    {
        if ($newsletter->delete()) {
            session()->flash('message', 'Newsletter successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Newsletter');
        }

        return redirect()->back();
    }
}
