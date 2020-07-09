<?php

namespace Blog\Policies;

use App\Models\User;
use Blog\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
     * Determine whether the user can view the Category.
     *
     * @param User     $user
     * @param Category $category
     *
     * @return mixed
     */
    public function view($user, Category $category)
    {
        return true;
    }

    /**
     * Determine whether the user can create Category.
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
     * Determine whether the user can update the Category.
     *
     * @param User     $user
     * @param Category $category
     *
     * @return mixed
     */
    public function update($user, Category $category)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Category.
     *
     * @param User     $user
     * @param Category $category
     *
     * @return mixed
     */
    public function delete($user, Category $category)
    {
        return false;
    }
}
