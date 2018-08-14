<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Permit\Models\User;

/**
 * @property int $id id
 * @property varchar $type slug
 */
class Activity extends Model
{
    const TYPE_LIKE = 'like';
    const TYPE_DISLIKE = 'dislike';
    const TYPE_FAVOURITE = 'favourite';
    const TYPE_LATER = 'later';
    /**
     * Database Table Name
     * @var string
     */
    protected $table = 'activities';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function activityable()
    {
        return $this->morphTo();
    }

    /**
     * Protected column that will not be mass assignable
     * @var array
     */
    protected $fillable = ['activityable_type', 'activityable_id', 'type'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->user_id) && auth()->check()) {
                $model->user_id = auth()->user()->id;
            }
            return true;
        });
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function words()
    {

    }

    public static function actions($user_id = '')
    {
        return ActivityType::forUser($user_id);
    }

    /**
     * @param $query
     * @param $request
     * @return
     */
    public function scopeForUser($query, Request $request)
    {
        return $query->where('user_id', auth()->user()->id)
            ->where('activityable_type', $request->get('activityable_type'))
            ->where('activityable_id', $request->get('activityable_id'))
            ->where('type', $request->get('type'));
    }

    public  function scopeLatest($query,$type)
    {
        return $query->where('user_id', auth()->user()->id)
            ->where('activityable_type', $type)
            ->orderBy('created_at', 'desc');
    }

}