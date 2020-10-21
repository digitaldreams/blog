<?php


namespace Blog\Services;


use Blog\Models\Post;
use Blog\Repositories\PostRepository;

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

}
