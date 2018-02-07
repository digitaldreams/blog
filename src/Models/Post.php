<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Permit\Models\User;

/**
 * @property int $user_id user id
 * @property varchar $title title
 * @property varchar $status status
 * @property varchar $body body
 * @property int $category_id category id
 * @property varchar $image image
 * @property datetime $published_at published at
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 * @property Category $category belongsTo
 * @property User $user belongsTo
 */
class Post extends Model
{

    /**
     * Database table name
     */
    protected $table = 'blog_posts';
    /**
     * Protected columns from mass assignment
     */
    protected $guarded = ['id'];


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
                $model->slug = str_slug($model->title);
            }
            return true;
        });
        parent::boot(); // TODO: Change the autogenerated stub
    }

    /**
     * category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('blog.userModel'), 'user_id');
    }

    /**
     * title column mutator.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = htmlspecialchars($value);
    }

    /**
     * status column mutator.
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = htmlspecialchars($value);
    }

    /**
     * body column mutator.
     */
    public function setBodyAttribute($value)
    {
        $this->attributes['body'] = htmlspecialchars($value);
    }

    public function getContentAttribute($value)
    {
        return htmlspecialchars_decode($this->body);
    }

    /**
     * published_at column mutator. Date will be converted automatically to db format before saving.
     */
    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function getImageUrl()
    {
        return asset('storage' . '/' . $this->image);
    }

    /**
     * @param $query
     * @param $keyword
     * @return mixed
     */
    public function scopeQ($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->orWhere('title', 'LIKE', '%' . $keyword . '%');
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

}