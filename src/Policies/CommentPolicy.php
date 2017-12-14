<?php

namespace Blog\Policies;

use \Blog\Models\Comment;
use Permit\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function before(User $user)
    {
        //return true if user has super power
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
     * Determine whether the user can view the Comment.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function view(User $user, Comment  $comment)
    {
        return true;
    }
    /**
     * Determine whether the user can create Comment.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }
    /**
     * Determine whether the user can update the Comment.
     *
     * @param User $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment  $comment)
    {
        return true;
    }
    /**
     * Determine whether the user can delete the Comment.
     *
     * @param User  $user
     * @param  Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment  $comment)
    {
        return true;
    }

}
