<?php

namespace Blog\Repositories;

use Blog\Models\Tag;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TagRepository
{
    /**
     * @var \Blog\Models\Tag
     */
    protected $model;

    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $databaseManager;

    /**
     * TagRepository constructor.
     *
     * @param \Blog\Models\Tag                     $tag
     * @param \Illuminate\Database\DatabaseManager $databaseManager
     */
    public function __construct(Tag $tag, DatabaseManager $databaseManager)
    {
        $this->model = $tag;
        $this->databaseManager = $databaseManager;
    }

    /**
     * @param array $tags
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function saveTags(array $tags)
    {
        $dbTags = $this->model->newQuery()->whereIn('name', $tags)->get();
        if (count($dbTags) < count($tags)) {
            $remainingTags = array_diff($tags, $dbTags->pluck('name')->toArray());
            $insertAbleTag = [];
            foreach ($remainingTags as $rtag) {
                $insertAbleTag[] = [
                    'slug' => $this->generateUniqueSlug($rtag),
                    'name' => $rtag,
                ];
            }
            $this->databaseManager->table($this->model->getTable())->insert($insertAbleTag);
        }

        return $this->model->newQuery()->whereIn('name', $tags)->get();
    }

    /**
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function popular(int $limit = 10): Collection
    {
        return $this->model->newQuery()
            ->selectRaw('id,name,slug, (select count(*) from blog_post_tag where blog_post_tag.tag_id=blog_tags.id) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')
            ->take($limit)
            ->get();
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function generateUniqueSlug($name): string
    {
        $slug = Str::slug($name);

        return $this->model->newQuery()->where('slug', $slug)->exists() ? $slug . '-' . rand(1, 1000) : $slug;
    }
}
