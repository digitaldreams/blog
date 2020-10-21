<?php

namespace Blog\Repositories;

use App\Models\User;
use Blog\Jobs\TableOfContentGeneratorJob;
use Blog\Models\Post;
use Blog\Notifications\NewPostApproval;
use Blog\Services\CheckProfanity;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
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
}
