<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property varchar $title title
 * @property varchar $slug slug
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 * @property \Illuminate\Database\Eloquent\Collection $post hasMany
 */
class Category extends Model
{

    /**
     * Database table name
     */
    protected $table = 'categories';
    /**
     * Protected columns from mass assignment
     */
    protected $guarded = ['id'];


    /**
     * Date time columns.
     */
    protected $dates = [];

    public static function boot()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = str_slug($model->title);
            }
            return true;
        });
        parent::boot(); // TODO: Change the autogenerated stub
    }

    /**
     * posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    /**
     * title column mutator.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = htmlspecialchars($value);
    }

    /**
     * slug column mutator.
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = htmlspecialchars($value);
    }

    /**
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

}