<?php

namespace Blog\Services;

use Blog\Models\Activity;

trait ActivityHelper
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Activity::class, 'activityable')->where('type', Activity::TYPE_LIKE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function inappropriates()
    {
        return $this->morphMany(Activity::class, 'activityable')->where('type', Activity::TYPE_INAPPROPRIATE);
    }

    public function canMakeActivity($action)
    {
    }

    /**
     * @param $action
     *
     * @return bool
     */
    public function hasActivity($action)
    {
        if (auth()->user()) {
            $count = Activity::where('activityable_type', get_class($this))
                ->where('activityable_id', $this->id)
                ->where('user_id', auth()->id())
                ->where('type', $action)
                ->count();

            return $count > 0 ? true : false;
        }

        return false;
    }

    public function inappropriateReason()
    {
        return [];
    }
}
