<?php

namespace Blog\Policies;

use App\Models\User;
use Blog\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     *
     * @return bool
     */
    public function before($user)
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function index($user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the Activity.
     *
     * @param User     $user
     * @param Activity $activity
     *
     * @return mixed
     */
    public function view($user, Activity $activity)
    {
        return true;
    }

    /**
     * Determine whether the user can create Activity.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function create($user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Activity.
     *
     * @param User     $user
     * @param Activity $activity
     *
     * @return mixed
     */
    public function update($user, Activity $activity)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the Activity.
     *
     * @param User     $user
     * @param Activity $activity
     *
     * @return mixed
     */
    public function delete($user, Activity $activity)
    {
        return false;
    }

}
