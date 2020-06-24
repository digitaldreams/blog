<?php

namespace Blog\Observers;

use Blog\Models\Category;
use Blog\Services\UniqueSlugGeneratorService;

class CategoryObserver
{
    /**
     * Handle the Category "creating" event.
     *
     * @param \Blog\Models\Category $category
     *
     * @return void
     */
    public function creating(Category $category): void
    {
        (new UniqueSlugGeneratorService())->createSlug($category, $category->title);
    }

}
