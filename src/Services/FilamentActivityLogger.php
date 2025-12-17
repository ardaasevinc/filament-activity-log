<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FilamentActivityLogger
{
    public static function log(
        string $action,
        string $resource,
        ?Model $record = null,
        ?array $oldData = null,
        ?array $newData = null
    ): void {
        ActivityLog::create([
            'user_id'   => Auth::id(),
            'resource'  => class_basename($resource),
            'action'    => $action,
            'model'     => $record ? get_class($record) : null,
            'model_id'  => $record?->id,
            'old_data'  => $oldData,
            'new_data'  => $newData,
            'ip'        => request()->ip(),
            'user_agent'=> request()->userAgent(),
        ]);
    }
}
