<?php

namespace Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Permit\Models\User;

/**
 * @property varchar $name name
 * @property varchar $email email
 * @property enum $status status
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class Newsletter extends Model
{
    const STATUS_SUBSCRIBED = 'subscribed';
    const STATUS_UNSUBSCRIBED = 'unsubscribed';

    /**
     * Database table name
     */
    protected $table = 'newsletters';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'name',
        'email',
        'status'
    ];

    /**
     * Date time columns.
     */
    protected $dates = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

}