<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Permit\Models\User;

/**
 * @property int $user_id user id
 * @property int $post_id post id
 * @property longtext $body body
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 * @property Post $post belongsTo
 * @property User $user belongsTo
 */
class Comment extends Model
{

    /**
     * Database table name
     */
    protected $table = 'comments';
    /**
     * Protected columns from mass assignment
     */
    protected $guarded = ['id'];


    /**
     * Date time columns.
     */
    protected $dates = [];

    /**
     * post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
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


}