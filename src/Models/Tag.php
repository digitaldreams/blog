<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $slug slug
 * @property int $name name
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 * @property Post $posts belongsToMany
 */
class Tag extends Model
{
    /**
     * Database table name
     */
    protected $table = 'blog_tags';
    /**
     * Protected columns from mass assignment
     */
    protected $fillable = ['slug', 'name'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = str_slug($model->name);
            }
            return true;
        });
    }

    /**
     * post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blog_post_tag');
    }

}