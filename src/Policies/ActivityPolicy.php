<?php

namespace Blog\Policies;

use Blog\Models\Activity;
use Permit\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the Activity.
     *
     * @param  User $user
     * @param  Activity $activity
     * @return mixed
     */
    public function view(User $user, Activity $activity)
    {
        return true;
    }

    /**
     * Determine whether the user can create Activity.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Activity.
     *
     * @param User $user
     * @param  Activity $activity
     * @return mixed
     */
    public function update(User $user, Activity $activity)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the Activity.
     *
     * @param User $user
     * @param  Activity $activity
     * @return mixed
     */
    public function delete(User $user, Activity $activity)
    {
        return false;
    }

}
