<?php

namespace Blog\Policies;

use \Blog\Models\Newsletter;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsletterPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index($user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the Newsletter.
     *
     * @param User $user
     * @param Newsletter $newsletter
     * @return mixed
     */
    public function view($user, Newsletter $newsletter)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can create Newsletter.
     *
     * @param User $user
     * @return mixed
     */
    public function create($user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the Newsletter.
     *
     * @param User $user
     * @param Newsletter $newsletter
     * @return mixed
     */
    public function update($user, Newsletter $newsletter)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Newsletter.
     *
     * @param User $user
     * @param Newsletter $newsletter
     * @return mixed
     */
    public function delete($user, Newsletter $newsletter)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Newsletter.
     *
     * @param User $user
     * @param Newsletter $newsletter
     * @return mixed
     */
    public function subscribe($user, Newsletter $newsletter)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the Newsletter.
     *
     * @param User $user
     * @param Newsletter $newsletter
     * @return mixed
     */
    public function unsubscribe($user, Newsletter $newsletter)
    {
        return true;
    }
}
