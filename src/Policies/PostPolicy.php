<?php

namespace Blog\Policies;

use \Blog\Models\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
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
     * Determine whether the user can view the Post.
     *
     * @param User $user
     * @param Post $post
     * @return mixed
     */
    public function view($user, Post $post)
    {
        return $post->user_id == $user->id;
    }

    /**
     * Determine whether the user can create Post.
     *
     * @param User $user
     * @return mixed
     */
    public function create($user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the Post.
     *
     * @param User $user
     * @param Post $post
     * @return mixed
     */
    public function update($user, Post $post)
    {
        return $user->id == $post->user_id;
    }

    /**
     * Determine whether the user can delete the Post.
     *
     * @param User $user
     * @param Post $post
     * @return mixed
     */
    public function delete($user, Post $post)
    {
        return $user->id == $post->user_id && $post->status !== Post::STATUS_PUBLISHED;
    }

    /**
     * Determine whether the user can delete the Post.
     *
     * @param User $user
     * @param Post $post
     * @return mixed
     */
    public function approve($user)
    {
        return false;
    }

}
