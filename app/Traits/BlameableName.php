<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait BlameableName
 *
 * Automatically sets created_by, updated_by, and deleted_by from the authenticated user's name.
 *
 * Usage: Add `use BlameableName;` to your model.
 *
 * Assumes the model has these nullable string columns:
 * - created_by (string, nullable)
 * - updated_by (string, nullable)
 * - deleted_by (string, nullable) - only for soft deletes
 */
trait BlameableName
{
    public static function bootBlameableName()
    {
        // Set created_by on creating
        static::creating(function ($model) {
            if (Auth::check() && $model->isFillable('created_by')) {
                $model->created_by = $model->created_by ?? Auth::user()->name;
            }
        });

        // Set updated_by on updating
        static::updating(function ($model) {
            if (Auth::check() && $model->isFillable('updated_by')) {
                $model->updated_by = Auth::user()->name;
            }
        });

        // Set deleted_by on deleting (for soft deletes)
        static::deleting(function ($model) {
            if (Auth::check() && $model->isFillable('deleted_by')) {
                // Only set if using soft deletes
                if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                    $model->deleted_by = Auth::user()->name;
                    $model->saveQuietly();
                }
            }
        });
    }
}
