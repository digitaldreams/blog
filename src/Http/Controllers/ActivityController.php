<?php

namespace Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Blog\Enums\ActivityType;
use Blog\Http\Requests\Activities\Destroy;
use Blog\Http\Requests\Activities\Store;
use Blog\Http\Requests\Activities\Update;
use Blog\Models\Activity;
use Blog\Notifications\FavouriteNotification;
use Blog\Notifications\LikeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

/**
 * Description of WordMeaningController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ActivityController extends Controller
{
    public function show(Request $request, $action)
    {
        $model = false;

        $activityAbleType = $request->get('type');
        $activityAbleId = $request->get('id');
        if (class_exists($activityAbleType)) {
            $model = $activityAbleType::find($activityAbleId);
            if (!$model) {
                return redirect()->back()->with('error', 'Activity Type does not exists any more');
            }
        } else {
            return redirect()->back()->with('error', 'Activity Type does not exists any more');
        }

        return view('blog::pages.activities.show', [
            'model' => $model,
            'action' => $action,
            'activities' => Activity::query()->where('activityable_type', $activityAbleType)
                ->where('activityable_id', $activityAbleId)
                ->where('type', $action)
                ->latest()
                ->paginate(10),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function store(Store $request)
    {
        $model = Activity::forUser($request)->first();
        if ($model) {
            $model->delete();

            return redirect()->back()->with('message', 'Your ' . $request->get('type') . ' is undo');
        } else {
            $activityAbleClass = $request->get('activityable_type');
            $activityAbleId = $request->get('activityable_id');
            $activityModel = new $activityAbleClass();
            $activityModel = $activityModel->find($activityAbleId);

            if (ActivityType::LIKE == $request->get('type')) {
                $notificationOb = new LikeNotification($activityModel, auth()->user());
            } elseif (ActivityType::INAPPROPRIATE == $request->get('type')) {
                //   $notificationOb = new InappropriateNotification($activityModel, auth()->user());
            } elseif (ActivityType::FAVOURITE == $request->get('type')) {
                $notificationOb = new FavouriteNotification($activityModel, auth()->user());
            }
            Notification::send(User::getAdmins(), $notificationOb);
            $model = new Activity();
        }
        $model->fill($request->all());

        if ($model->save()) {
            session()->flash('message', 'Thanks for ' . $request->get('type'));
        } else {
            session()->flash('error', 'Oops something went wrong while ' . $request->get('type'));
        }

        return redirect()->back();
    }

    /**
     * Update a existing resource in storage.
     *
     * @param Update   $request
     * @param Activity $activity
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, Activity $activity)
    {
        $activity->fill($request->all());

        if ($activity->save()) {
            $activity->tags()->sync($request->get('tags', []));
            session()->flash('message', 'Your activity successfully updated');

            return redirect()->back();
        } else {
            session()->flash('error', 'Oops something went wrong while updating Word');
        }

        return redirect()->back();
    }

    /**
     * Delete a  resource from  storage.
     *
     * @param Request  $request
     * @param Activity $activity
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy(Destroy $request, Activity $activity)
    {
        if ($activity->delete()) {
            session()->flash('message', 'Word  successfully deleted');
        } else {
            session()->flash('error', 'Error occurred while deleting Word');
        }

        return redirect()->back();
    }
}
