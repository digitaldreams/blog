<?php

namespace Blog\Repositories;

use App\Models\User;
use Blog\Jobs\TableOfContentGeneratorJob;
use Blog\Models\Post;
use Blog\Models\Tag;
use Blog\Notifications\NewPostApproval;
use Blog\Services\CheckProfanity;
use Illuminate\Database\DatabaseManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
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
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $databaseManager;
    /**
     * @var \Blog\Models\Tag
     */
    protected $tag;

    /**
     * PostRepository constructor.
     *
     * @param \Blog\Models\Post                    $post
     * @param \Photo\Repositories\PhotoRepository  $photoRepository
     * @param \Illuminate\Database\DatabaseManager $databaseManager
     */
    public function __construct(Post $post, Tag $tag, PhotoRepository $photoRepository, DatabaseManager $databaseManager)
    {
        $this->post = $post;
        $this->photoRepository = $photoRepository;
        $this->databaseManager = $databaseManager;
        $this->tag = $tag;
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

        return $this->notifyAndTags($this->post, $data['tags']);
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
            $this->photoRepository->delete($post->image);
            $this->post->image_id = $this->photoRepository->create($file, ['caption' => $data['title']]);
        }

        $post->save();

        return $this->notifyAndTags($post, $data['tags']);
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
        if ($post->delete()) {
            $this->photoRepository->delete($post->image);
        }

        return true;
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
            $model->tags()->sync($this->saveTags($tags));
        }

        return $model;
    }

    /**
     * @param array $tags
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function saveTags(array $tags)
    {
        $dbTags = $this->tag->newQuery()->whereIn('name', $tags)->get();
        if (count($dbTags) < count($tags)) {
            $remainingTags = array_diff($tags, $dbTags->pluck('name')->toArray());
            $insertAbleTag = [];
            foreach ($remainingTags as $rtag) {
                $insertAbleTag[] = [
                    'slug' => $this->generateUniqueSlug($rtag),
                    'name' => $rtag,
                ];
            }
            $this->databaseManager->table($this->tag->getTable())->insert($insertAbleTag);
        }

        return $this->tag->newQuery()->whereIn('name', $tags)->get();
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function generateUniqueSlug($name): string
    {
        $slug = Str::slug($name);

        return $this->tag->newQuery()->where('slug', $slug)->exists() ? $slug . '-' . rand(1, 1000) : $slug;
    }
}
