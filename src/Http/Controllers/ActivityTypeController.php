<?php

namespace Blog\Http\Controllers;

use Blog\Models\Activity;
use Illuminate\Http\Request;
use Blog\Models\ActivityType;
use Blog\Http\Requests\ActivityTypes\Index;
use Blog\Http\Requests\ActivityTypes\Show;
use Blog\Http\Requests\ActivityTypes\Create;
use Blog\Http\Requests\ActivityTypes\Store;
use Blog\Http\Requests\ActivityTypes\Update;
use Blog\Http\Requests\ActivityTypes\Destroy;


/**
 * Description of ActivityTypeController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Index $request
     * @return \Illuminate\Http\Response
     */
    public function index(Index $request)
    {
        return view('blog::pages.activity_types.index', ['records' => ActivityType::paginate(10)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Show $request
     * @param ActivityType $type
     * @return \Illuminate\Http\Response
     */
    public function show(Show $request, $type)
    {
        $typeModel = ActivityType::where('name', $type)->first();
        $post = $request->get('model');
        $list = config('blog.activityType', []);
        $model = isset($list[$post]) ? $list[$post] : $type;
        $activities = Activity::with('activityable')->where('type', $type)->latest($model)->paginate(6);

        return view('blog::pages.activity_types.show', [
            'record' => $typeModel,
            'activities' => $activities
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
        return view('blog::pages.activity_types.create', [
            'model' => new ActivityType,
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
        $model = new ActivityType;
        $model->fill($request->all());

        if ($model->save()) {
            session()->flash('message', 'Activity Type saved successfully');
            return redirect()->route('blog::types.index');
        } else {
            session()->flash('message', 'Oops something went wrong while saving Activity Type');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @param ActivityType $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ActivityType $type)
    {
        return view('blog::pages.activity_types.edit', [
            'model' => $type,
        ]);
    }

    /**
     * Update a existing resource in storage.
     *
     * @param  Update $request
     * @param ActivityType $type
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, ActivityType $type)
    {
        $type->fill($request->all());

        if ($type->save()) {

            session()->flash('message', 'Activity Type successfully updated');
            return redirect()->route('blog::types.index');
        } else {
            session()->flash('error', 'Oops something went wrong while updating Activity Type');
        }
        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param  Destroy $request
     * @param ActivityType $type
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, ActivityType $type)
    {
        if ($type->delete()) {
            session()->flash('message', 'Activity Type successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Activity Type');
        }
        return redirect()->back();
    }
}
