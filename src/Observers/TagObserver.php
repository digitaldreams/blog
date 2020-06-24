<?php

namespace Blog\Observers;

use Blog\Models\Tag;
use Blog\Services\UniqueSlugGeneratorService;

class TagObserver
{
    /**
     * Handle the Tag "creating" event.
     *
     * @param \Blog\Models\Tag $tag
     *
     * @return void
     */
    public function creating(Tag $tag): void
    {
        (new UniqueSlugGeneratorService())->createSlug($tag, $tag->name);
    }
}
