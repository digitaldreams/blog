<?php

namespace Blog\Policies;

use Blog\Models\ActivityType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityTypePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index($user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the ActivityType.
     *
     * @param  User $user
     * @param  ActivityType $activityType
     * @return mixed
     */
    public function view($user, ActivityType $activityType)
    {
        return empty($activityType->user_id) || $activityType->user_id == $user->id;
    }

    /**
     * Determine whether the user can create ActivityType.
     *
     * @param  User $user
     * @return mixed
     */
    public function create($user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the ActivityType.
     *
     * @param User $user
     * @param  ActivityType $activityType
     * @return mixed
     */
    public function update($user, ActivityType $activityType)
    {
        return $activityType->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the ActivityType.
     *
     * @param User $user
     * @param  ActivityType $activityType
     * @return mixed
     */
    public function delete($user, ActivityType $activityType)
    {
        return $activityType->user_id == $user->id;
    }

}
