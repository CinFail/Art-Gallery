<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
    ];

    // The user who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The record that was affected (polymorphic)
    public function subject()
    {
        return $this->morphTo();
    }
}
