<?php

namespace Blog\Observers;

use Blog\Models\Activity;

class ActivityObserver
{
    /**
     * Handle the Activity "creating" event.
     *
     * @param \Blog\Models\Activity $activity
     *
     * @return void
     */
    public function creating(Activity $activity): void
    {
        if (empty($activity->user_id) && auth()->check()) {
            $activity->user_id = auth()->id();
        }
    }

}
