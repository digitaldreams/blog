<?php

namespace Blog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

/**
 * @property int    $id   id
 * @property string $type slug
 */
class Activity extends Model
{

    /**
     * Database Table Name.
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * Protected column that will not be mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activityable_type',
        'activityable_id',
        'type',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function activityable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Owner of the Activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param string $user_id
     *
     * @return mixed
     */
    public static function actions($user_id = '')
    {
        return ActivityType::forUser($user_id);
    }

    /**
     * Filter Current user Activity.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request              $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query, Request $request): Builder
    {
        return $query->where('user_id', auth()->user()->id)
            ->where('activityable_type', $request->get('activityable_type'))
            ->where('activityable_id', $request->get('activityable_id'))
            ->where('type', $request->get('type'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $type
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest(Builder $query, $type): Builder
    {
        return $query->where('user_id', auth()->user()->id)
            ->where('activityable_type', $type)
            ->orderBy('created_at', 'desc');
    }
}
