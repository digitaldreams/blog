<?php

namespace Blog\Policies;

use \Blog\Models\Tag;
use Permit\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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
     * Determine whether the user can view the Category.
     *
     * @param  User $user
     * @param  Tag $tag
     * @return mixed
     */
    public function view(User $user, Tag $tag)
    {
        return true;
    }

    /**
     * Determine whether the user can create Category.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Category.
     *
     * @param User $user
     * @param  Tag $tag
     * @return mixed
     */
    public function update(User $user, Tag $tag)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Category.
     *
     * @param User $user
     * @param  Tag $tag
     * @return mixed
     */
    public function delete(User $user, Tag $tag)
    {
        return false;
    }

}
