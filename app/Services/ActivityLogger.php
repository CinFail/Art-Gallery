<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity.
     *
     * @param string $action  'create', 'update', 'delete', 'login', 'logout'
     * @param string $description Human-readable description
     * @param string|null $module   'Artist', 'Artwork'
     * @param Model|null $subject  The Eloquent model that was affected
     */
    public static function log(
        string $action,
        string $description,
        ?string $module = null,
        ?Model $subject = null
    ): void {
        $user = Auth::user();

        ActivityLog::create([
            'user_id'      => $user?->id,
            'user_name'    => $user?->name ?? 'System',
            'action'       => $action,
            'module'       => $module,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
            'description'  => $description,
            'ip_address'   => Request::ip(),
        ]);
    }
}
