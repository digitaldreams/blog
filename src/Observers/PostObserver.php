<?php

namespace Blog\Observers;

use Blog\Models\Post;
use Blog\Services\UniqueSlugGeneratorService;
use SEO\Seo;

class PostObserver
{
    /**
     * Handle the Platform "creating" event.
     *
     * @param \Blog\Models\Post $post
     *
     * @return void
     */
    public function creating(Post $post): void
    {
        (new UniqueSlugGeneratorService())->createSlug($post, $post->title);
    }

    /**
     * Handle the Post "saved" event.
     *
     * @param \Blog\Models\Post $post
     *
     * @return void
     */
    public function saved(Post $post): void
    {
        if (Post::STATUS_PUBLISHED === $post->status && empty($post->published_at)) {
            $post->published_at = date('Y-m-d H:i:s');
            $post->save();
        }
        Seo::save($post, route('blog::frontend.blog.posts.show', [
            'category' => $post->category->slug,
            'post' => $post->slug,
        ]), [
            'title' => $post->title,
            'images' => [
                $post->getImageUrl(),
            ],
        ]);
    }
}
