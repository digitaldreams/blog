<?php

namespace Blog\Repositories;

use App\Models\User;
use Blog\Jobs\TableOfContentGeneratorJob;
use Blog\Models\Category;
use Blog\Models\Post;
use Blog\Models\Tag;
use Blog\Notifications\NewPostApproval;
use Blog\Services\CheckProfanity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;
use Photo\Repositories\PhotoRepository;

class PostRepository
{
    /**
     * @var \Blog\Models\Post
     */
    protected Post $post;

    /**
     * @var \Photo\Repositories\PhotoRepository
     */
    protected PhotoRepository $photoRepository;

    /**
     * @var \Blog\Repositories\TagRepository
     */
    protected $tagRepository;

    /**
     * PostRepository constructor.
     *
     * @param \Blog\Models\Post                   $post
     * @param \Blog\Repositories\TagRepository    $tagRepository
     * @param \Photo\Repositories\PhotoRepository $photoRepository
     */
    public function __construct(Post $post, TagRepository $tagRepository, PhotoRepository $photoRepository)
    {
        $this->post = $post;
        $this->photoRepository = $photoRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param array                         $data
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Blog\Models\Post|bool
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function create(array $data, UploadedFile $file)
    {
        $this->post->setImageSize();
        $this->post->fill($data);

        if ($this->hasProfanity($this->post)) {
            return false;
        }

        $this->post->image_id = $this->photoRepository->create($file, ['caption' => $data['title']])->id;
        $this->post->save();

        return $this->notifyAndTags($this->post, $data['tags'] ?? []);
    }

    /**
     * @param array                         $data
     * @param \Blog\Models\Post             $post
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Blog\Models\Post|bool
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function update(array $data, Post $post, ?UploadedFile $file = null)
    {
        $this->post->setImageSize();
        $post->fill($data);

        if ($this->hasProfanity($post)) {
            return false;
        }

        if ($file) {
            if ($post->image) {
                $this->photoRepository->delete($post->image);
            }
            $this->post->image_id = $this->photoRepository->create($file, ['caption' => $data['title']]);
        }

        $post->save();

        return $this->notifyAndTags($post, $data['tags'] ?? []);
    }

    /**
     * @param \Blog\Models\Post $post
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function delete(Post $post)
    {
        $post->delete();
        if ($post->image) {
            $this->photoRepository->delete($post->image);
        }

        return true;
    }

    /**
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function featuredPosts(int $limit = 4): Collection
    {
        return $this->post->newQuery()->where('status', Post::STATUS_PUBLISHED)
            ->where('is_featured', Post::IS_FEATURED)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function latestPosts(int $limit = 6): Collection
    {
        return $this->post->newQuery()->where('status', Post::STATUS_PUBLISHED)
            ->where('is_featured', 0)->orderBy('created_at', 'desc')
            ->take($limit)->get();
    }

    /**
     * @param \Blog\Models\Post $post
     * @param int               $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function relatedPosts(Post $post, int $limit = 4): Collection
    {
        return Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->orderBy('total_view', 'desc')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * @param $model
     *
     * @return bool
     */
    protected function hasProfanity($model): bool
    {
        $checkProfanity = new CheckProfanity($model);

        return $checkProfanity->check();
    }

    /**
     * @param            $model
     * @param array|null $tags
     *
     * @return mixed
     */
    protected function notifyAndTags(Post $model, ?array $tags = null): Post
    {
        dispatch(new TableOfContentGeneratorJob($model));

        if (!auth()->user()->can('approve', Post::class)) {
            Notification::send(User::getAdmins(), new NewPostApproval($model));
            session()->flash('message', 'Post saved successfully and one of our moderator will review it soon');
        } else {
            session()->flash('message', 'Post saved successfully');
        }
        if ($tags) {
            $model->tags()->sync($this->tagRepository->saveTags($tags));
        }

        return $model;
    }

    /**
     * @param string $search
     * @param int    $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function search(string $search, int $perPage = 6): LengthAwarePaginator
    {
        $builder = $this->post->newQuery()->selectRaw('blog_posts.*,
             match(blog_posts.title,blog_posts.body) against ("' . $search . '" IN NATURAL LANGUAGE MODE) as bscore')
            ->distinct()
            ->leftJoin('blog_categories', 'blog_posts.category_id', '=', 'blog_categories.id')
            ->leftJoin('blog_post_tag', 'blog_posts.id', '=', 'blog_post_tag.post_id')
            ->leftJoin('blog_tags', 'blog_post_tag.tag_id', '=', 'blog_tags.id');

        $keywords = $this->fullTextWildCards($search);

        foreach ($keywords as $keyword) {
            $builder = $this->searchBykeyword($builder, $keyword);
        }

        return $builder->where('status', Post::STATUS_PUBLISHED)
            ->orderByRaw('bscore desc')
            ->paginate($perPage);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int                                   $userId
     * @param int                                   $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function preferences(int $userId, int $perPage = 6): LengthAwarePaginator
    {
        return $this->post->newquery()->selectRaw("*, 
        (select count(*) from preferences where category_id=blog_posts.category_id and user_id='{$userId}') as cscore,
        (select count(*) from preferences where tag_id IN (select tag_id from blog_post_tag where post_id=blog_posts.id) and user_id={$userId}) as tscore
        ")
            ->where('status', Post::STATUS_PUBLISHED)
            ->orderByRaw('cscore*2+tscore desc')
            ->paginate($perPage);
    }

    /**
     * @param int $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function popular(int $perPage): LengthAwarePaginator
    {
        return $this->post->newQuery()
            ->where('status', Post::STATUS_PUBLISHED)
            ->paginate($perPage);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param string                                $search
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function searchBykeyword(Builder $builder, string $search): Builder
    {
        return $builder->where(function ($q) use ($search) {
            $q->orWhereRaw('match(blog_posts.title,blog_posts.body) against (? IN NATURAL LANGUAGE MODE)', $search)
                ->orWhereRaw('match(blog_categories.title) against (? IN NATURAL LANGUAGE MODE)', $search)
                ->orWhereRaw('match(blog_tags.name) against (? IN NATURAL LANGUAGE MODE)', $search);
        });
    }

    /**
     * @param string $term
     * @param string $start
     * @param string $end
     *
     * @return array
     */
    private function fullTextWildCards(string $term, $start = '+', $end = '*'): array
    {
        $reservedSymbols = ['-', '+', '"', "'", '<', '>', '@', '(', ')', '~', '*'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = $start . $word . $end;
            }
        }

        return $words;
    }

    /**
     * @return array|\Illuminate\Cache\CacheManager|mixed
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */
    public function keywords()
    {
        $key = 'posts_category_tags_keywords';
        if (cache()->has($key)) {
            return cache($key);
        }

        $categories = Category::query()
            ->selectRaw('title as name,(select count(*) from blog_posts where blog_posts.category_id=blog_categories.id ) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')->get()->toArray();

        $tags = Tag::query()
            ->selectRaw('name, (select count(*) from blog_post_tag where blog_post_tag.tag_id=blog_tags.id) as total')
            ->havingRaw('total > 0 ')
            ->orderByRaw('total desc')
            ->get()->toArray();

        $data = array_merge($categories, $tags);

        cache()->put($key, $data, now()->addDay());

        return $data;
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function publishedBetween(string $start = 'now', string $end = '-24 hours'): Collection
    {
        return $this->post->newQuery()
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereBetween('published_at', [
                (new \DateTime($start))->format('Y-m-d H:i:s'),
                (new \DateTime($end))->format('Y-m-d H:i:s'),
            ])->get();
    }

    /**
     * @param \Blog\Models\Post $post
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findPreferredUsersForPost(Post $post): Collection
    {
        $category = $post->category_id;
        $tags = $post->tags()->allRelatedIds();

        return User::query()->where(function ($q) use ($category, $tags) {
            $q->orWhereHas('preferredCategories', function ($sq) use ($category) {
                $sq->where('category_id', $category);
            })->orWhereHas('preferredTags', function ($tg) use ($tags) {
                $tg->whereIn('tag_id', $tags);
            });
        })->get();
    }
}
