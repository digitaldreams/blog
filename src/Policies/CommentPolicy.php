<?php

namespace Blog\Policies;

use App\Models\User;
use Blog\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
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
     * Determine whether the user can view the Comment.
     *
     * @param User    $user
     * @param Comment $comment
     *
     * @return mixed
     */
    public function view($user, Comment $comment)
    {
        return true;
    }

    /**
     * Determine whether the user can create Comment.
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
     * Determine whether the user can update the Comment.
     *
     * @param User    $user
     * @param Comment $comment
     *
     * @return mixed
     */
    public function update($user, Comment $comment)
    {
        return $user->id == $comment->user_id;
    }

    /**
     * Determine whether the user can delete the Comment.
     *
     * @param User    $user
     * @param Comment $comment
     *
     * @return mixed
     */
    public function delete($user, Comment $comment)
    {
        return $user->id == $comment->user_id;
    }
}
