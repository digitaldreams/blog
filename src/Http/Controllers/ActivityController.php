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
use Illuminate\Translation\Translator;
/**
 * Description of WordMeaningController.
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
class ActivityController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Translation\Translator
     */
    protected $translator;

    /**
     * ActivityController constructor.
     *
     * @param \Illuminate\Contracts\Translation\Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request, $action)
    {
        $model = false;

        $activityAbleType = $request->get('type');
        $activityAbleId = $request->get('id');
        if (class_exists($activityAbleType)) {
            $model = $activityAbleType::find($activityAbleId);
            if (!$model) {
                return redirect()->back()->with('error', $this->translator->get('blog::flash.notExists', ['model' => 'Activity Type']));
            }
        } else {
            return redirect()->back()->with('error', $this->translator->get('blog::flash.notExists', ['model' => 'Activity Type']));
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

            return redirect()->back()->with('message', $this->translator->get('blog::flash.undo', ['action' => $request->get('type')]));
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
            session()->flash('message', $this->translator->get('blog::flash.thanksFor', ['action' => $request->get('type')]));
        } else {
            session()->flash('error', $this->translator->get('blog::flash.oops', ['action' => $request->get('type')]));
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
            session()->flash('message', $this->translator->get('blog::flash.updated', ['model' => 'Your Activity']));

            return redirect()->back();
        } else {
            session()->flash('error', $this->translator->get('blog::flash.oops', ['action' => 'updating action']));
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
            session()->flash('message', $this->translator->get('blog::flash.deleted', ['model' => $activity->type]));
        } else {
            session()->flash('error', $this->translator->get('blog::flash.errorOccurred', ['action' => 'deleting Activity']));
        }

        return redirect()->back();
    }
}
