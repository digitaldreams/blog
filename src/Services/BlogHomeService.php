<?php

namespace Blog\Services;

use App\Models\User;
use Blog\Models\Post;
use Blog\Repositories\PostRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogHomeService
{
    /**
     * @var \Blog\Repositories\PostRepository
     */
    protected $postRepository;

    /**
     * BlogHomeService constructor.
     *
     * @param \Blog\Repositories\PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function get(?string $search = null, ?User $user = null, int $perPage = 6)
    {
        if (!empty($search)) {
            return $this->postRepository->search($search, $perPage);
        }

        if ($user) {
            return $this->postRepository->preferences($user->id, $perPage);
        }

        return $this->postRepository->popular($perPage);
    }


}
