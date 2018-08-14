<?php

namespace Blog\Http\Controllers;

use Blog\Http\Requests\Activities\Create;
use Blog\Http\Requests\Activities\Destroy;
use Blog\Http\Requests\Activities\Edit;
use Blog\Http\Requests\Activities\Index;
use Blog\Http\Requests\Activities\Show;
use Blog\Http\Requests\Activities\Store;
use Blog\Http\Requests\Activities\Update;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Blog\Models\Activity;
use Permit\Models\User;
use Photo\Models\Photo;
use Photo\Services\PhotoService;
use Blog\Models\Tag;

/**
 * Description of WordMeaningController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ActivityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Store $request)
    {
        $model = Activity::forUser($request)->first();
        if ($model) {
            $model->delete();
            return redirect()->back()->with('permit_message', 'Your ' . $request->get('type') . ' is undo');
        } else {
            $model = new Activity();
        }
        $model->fill($request->all());

        if ($model->save()) {
            session()->flash('permit_message', 'Thanks for ' . $request->get('type'));
        } else {
            session()->flash('permit_error', 'Something is wrong while ' . $request->get('type'));
        }
        return redirect()->back();
    }

    /**
     * Update a existing resource in storage.
     *
     * @param  Request $request
     * @param  Activity $activity
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Update $request, Activity $activity)
    {
        $activity->fill($request->all());

        if ($activity->save()) {
            $activity->tags()->sync($request->get('tags', []));
            session()->flash('permit_message', 'Your activity successfully updated');
            return redirect()->back();
        } else {
            session()->flash('permit_error', 'Something is wrong while updating Word');
        }
        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param  Request $request
     * @param  Activity $activity
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Destroy $request, Activity $activity)
    {
        if ($activity->delete()) {
            session()->flash('permit_message', 'Word  successfully deleted');
        } else {
            session()->flash('permit_error', 'Error occurred while deleting Word');
        }

        return redirect()->back();
    }
}
