<?php

namespace Blog\Models;

use App\Models\User;
use Blog\Services\ActivityHelper;
use Blog\Services\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Photo\Models\Photo;

/**
 * @property int       $user_id          user id
 * @property varchar   $title            title
 * @property varchar   $status           status
 * @property string    $table_of_content status
 * @property varchar   $body             body
 * @property int       $category_id      category id
 * @property Photo     $image            image
 * @property datetime  $published_at     published at
 * @property timestamp $created_at       created at
 * @property timestamp $updated_at       updated at
 * @property Category  $category         belongsTo
 * @property User      $user             belongsTo
 */
class Post extends Model
{
    use ActivityHelper, FullTextSearch;
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';
    const  IS_FEATURED = 1;
    /**
     * Database table name.
     */
    protected $table = 'blog_posts';
    /**
     * Protected columns from mass assignment.
     */
    protected $fillable = [
        'title',
        'slug',
        'status',
        'body',
        'category_id',
        'image_id',
        'published_at',
        'is_featured',
        'total_view',
    ];

    protected $searchable = [
        'title',
        'body',
    ];
    /**
     * Date time columns.
     */
    protected $dates = ['published_at'];

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->user_id) && auth()->check()) {
                $model->user_id = auth()->user()->id;
            }
            if (empty($model->slug)) {
                $slug = Str::slug($model->title);
                $exists = Post::where('slug', $slug)->count();
                $model->slug = 0 == $exists ? $slug : $slug . '-' . rand(999, 100000);
            }

            return true;
        });
        parent::boot();
    }

    /**
     * category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Photo::class, 'image_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_post_tag');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'activityable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Activity::class, 'activityable')
            ->where('type', \Blog\Enums\ActivityType::LIKE);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favourites()
    {
        return $this->morphMany(Activity::class, 'activityable')
            ->where('type', \Blog\Enums\ActivityType::FAVOURITE);
    }

    /**
     * user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('blog.userModel'), 'user_id');
    }

    public function getImageUrl()
    {
        if (is_object($this->image)) {
            return $this->image->getUrl();
        }

        return config('blog.defaultPhoto');
    }

    public function scopeTagId($query, $id)
    {
        return $query->whereHas('tags', function ($q) use ($id) {
            $q->where('id', $id);
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function incrementViewCount()
    {
        $ip = request()->ip();
        $key = $ip . '_posts_views_' . $this->id;
        if (!Cache::has($key)) {
            $this->increment('total_view');
            Cache::put($key, $this->id, 1440);

            return true;
        }

        return false;
    }

    public function tagIds()
    {
        try {
            return $this->tags()->allRelatedIds()->toArray();
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function setImageSize()
    {
        config([
            'photo.maxWidth' => 450,
            'photo.maxHeight' => 304,
            'photo.sizes.thumbnail.width' => 288,
            'photo.sizes.thumbnail.height' => 238,
            'photo.rootPath' => 'posts',
        ]);
    }

    public function getSummary($wordCount = 250)
    {
        return substr(strip_tags($this->body), 0, $wordCount) . '...';
    }

    /**
     * @return string
     */
    public function link()
    {
        return route('blog::frontend.blog.posts.show', [
            'category' => $this->category->slug,
            'blog' => $this->slug,
        ]);
    }

    /**
     * @return array
     */
    public function breadcrumb()
    {
        return [
            'posts' => route('blog::frontend.blog.posts.index'),
            $this->name => route('blog::frontend.blog.posts.show', [
                'category' => $this->category->slug,
                'blog' => $this->slug,
            ]),
        ];
    }

    /**
     * @return mixed
     */
    public function owner()
    {
        return $this->user;
    }

    public function breadcrumbList()
    {
        $position = 0;
        $data = [
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
        ];
        $itemList = [];
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => ++$position,
            'item' => [
                '@id' => url('/'),
                'name' => 'Blog',
            ],
        ];
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => ++$position,
            'item' => [
                '@id' => route('blog::posts.home'),
                'name' => 'Home',
            ],
        ];

        if (is_object($this->category->parentCategory)) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => ++$position,
                'item' => [
                    '@id' => route('blog::frontend.blog.categories.index', ['category' => $this->category->parentCategory->slug]),
                    'name' => $this->category->parentCategory->title,
                ],
            ];
        }
        if (is_object($this->category)) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => ++$position,
                'item' => [
                    '@id' => route('blog::frontend.blog.categories.index', ['category' => $this->category->slug]),
                    'name' => $this->category->title,
                ],
            ];
        }
        $itemList[] = [
            '@type' => 'ListItem',
            'position' => ++$position,
            'item' => [
                '@id' => route('blog::frontend.blog.posts.show', ['category' => $this->category->slug, 'post' => $this->slug]),
                'name' => $this->title,
            ],
        ];
        $data['itemListElement'] = $itemList;

        return $data;
    }
}
