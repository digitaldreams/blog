<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string    $slug       slug
 * @property int       $name       name
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 * @property Post      $posts      belongsToMany
 */
class Tag extends Model
{
    /**
     * Database table name.
     */
    protected $table = 'blog_tags';
    /**
     * Protected columns from mass assignment.
     */
    protected $fillable = ['slug', 'name', 'description'];

    /**
     * post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'blog_post_tag');
    }

    /**
     * @param $query
     * @param $keyword
     *
     * @return mixed
     */
    public function scopeQ($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function preferredUsers()
    {
        $user = config('auth.providers.users.model');

        return $this->belongsToMany($user, 'preferences');
    }
}
