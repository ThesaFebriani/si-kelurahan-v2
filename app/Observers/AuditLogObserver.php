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

        if ($action === AuditLog::ACTION_CREATE) {
            $newData = $model->toArray();
            $description = "Membuat data baru pada " . class_basename($model);
        } elseif ($action === AuditLog::ACTION_DELETE) {
            $oldData = $model->toArray();
            $description = "Menghapus data pada " . class_basename($model);
        } elseif ($action === AuditLog::ACTION_UPDATE) {
            $changes = $model->getChanges();
            $original = $model->getOriginal();
            
            // Only capture changed fields
            $oldData = [];
            $newData = [];
            
            foreach ($changes as $key => $value) {
                if ($key === 'updated_at') continue; // Skip timestamp
                
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
