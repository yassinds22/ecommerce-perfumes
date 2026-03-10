<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log a specific action.
     *
     * @param string $action
     * @param string|null $description
     * @param mixed $subject
     * @return void
     */
    public function logActivity(string $action, ?string $description = null, $subject = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
