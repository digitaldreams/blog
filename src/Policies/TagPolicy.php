<?php

namespace Blog\Policies;

use \Blog\Models\Tag;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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
     * Determine whether the user can view the Category.
     *
     * @param  User $user
     * @param  Tag $tag
     * @return mixed
     */
    public function view($user, Tag $tag)
    {
        return true;
    }

    /**
     * Determine whether the user can create Category.
     *
     * @param  User $user
     * @return mixed
     */
    public function create($user)
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
    public function update($user, Tag $tag)
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
    public function delete($user, Tag $tag)
    {
        return false;
    }

}
