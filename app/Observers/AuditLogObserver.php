<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->logActivity(AuditLog::ACTION_CREATE, $model);
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        // Ignore if only 'updated_at' changed (not meaningful update)
        if (count($model->getChanges()) === 1 && $model->wasChanged('updated_at')) {
            return;
        }
        
        $this->logActivity(AuditLog::ACTION_UPDATE, $model);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->logActivity(AuditLog::ACTION_DELETE, $model);
    }

    /**
     * Log activity to database.
     */
    protected function logActivity(string $action, Model $model)
    {
        // Don't log if no user logged in (e.g. seeder or system job), unless it's critical
        if (!Auth::check()) {
            return;
        }

        $userId = Auth::id();
        $modelType = get_class($model);
        $modelId = $model->getKey();
        
        $oldData = null;
        $newData = null;

        // Fields to exclude from log for security and space saving
        $excludedFields = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at'];

        if ($action === AuditLog::ACTION_CREATE) {
            $newData = $model->toArray();
            // Filter excluded fields
            $newData = array_diff_key($newData, array_flip($excludedFields));
            $description = "Membuat data baru pada " . class_basename($model);
        } elseif ($action === AuditLog::ACTION_DELETE) {
            $oldData = $model->toArray();
            // Filter excluded fields
            $oldData = array_diff_key($oldData, array_flip($excludedFields));
            $description = "Menghapus data pada " . class_basename($model);
        } elseif ($action === AuditLog::ACTION_UPDATE) {
            $changes = $model->getChanges();
            $original = $model->getOriginal();
            
            // Only capture changed fields
            $oldData = [];
            $newData = [];
            
            foreach ($changes as $key => $value) {
                if (in_array($key, $excludedFields)) continue; // Skip excluded fields
                
                $oldData[$key] = $original[$key] ?? null;
                $newData[$key] = $value;
            }
            
            if (empty($newData)) return; // No real changes
            
            $description = "Memperbarui data pada " . class_basename($model);
        }

        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description ?? $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
