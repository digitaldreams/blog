<?php


namespace Blog\Repositories;


use Blog\Models\Category;

class CategoryRepository
{
    /**
     * @var \Blog\Models\Category
     */
    protected $model;

    /**
     * CategoryRepository constructor.
     *
     * @param \Blog\Models\Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function popular(int $limit = 6)
    {
        return $this->model->newQuery()
            ->selectRaw('id,title,slug,(select count(*) from blog_posts where blog_posts.category_id=blog_categories.id) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')
            ->take($limit)
            ->get();
    }
}
