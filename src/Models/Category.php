<?php

namespace Blog\Models;

use Blog\Services\FullTextSearch;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string                                   $title      title
 * @property string                                   $slug       slug
 * @property \Carbon\Carbon                           $created_at created at
 * @property \Carbon\Carbon                           $updated_at updated at
 * @property \Illuminate\Database\Eloquent\Collection $post       hasMany
 */
class Category extends Model
{
    use FullTextSearch;

    /**
     * Database table name.
     */
    protected $table = 'blog_categories';

    /**
     * Protected columns from mass assignment.
     */
    protected $fillable = ['parent_id', 'title', 'slug'];

    /**
     * @var array
     */
    protected $searchable = ['title'];

    /**
     * posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
