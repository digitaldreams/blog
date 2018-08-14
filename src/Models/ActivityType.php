<?php

namespace Blog\Models;

use Exam\Models\Exam;
use Illuminate\Database\Eloquent\Model;
use Permit\Models\User;
use App\Models\WordMeaning;

/**
 * @property varchar $name name
 * @property int $user_id user id
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 * @property User $user belongsTo
 */
class ActivityType extends Model
{

    /**
     * Database table name
     */
    protected $table = 'activity_types';

    /**
     * Mass assignable columns
     */
    protected $fillable = ['name', 'user_id'];
    /**
     * @var array
     */
    public static $map = [
        'posts' => Post::class,
        'words' => WordMeaning::class,
        'exams' => Exam::class
    ];
    /**
     * Date time columns.
     */
    protected $dates = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (auth()->check() && !auth()->user()->isSuperAdmin()) {
                $model->user_id = auth()->id();
            }
        });
    }

    /**
     * user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function forUser($user_id = '')
    {
        $user_id = empty($user_id) && auth()->check() ? auth()->id() : $user_id;
        return static::where(function ($q) use ($user_id) {
            return $q->orWhere('user_id', $user_id)
                ->orWhereNull('user_id');
        })->get(['name'])
            ->pluck('name', 'name')
            ->toArray();
    }

    public static function map()
    {
        return;
    }


}