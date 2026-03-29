<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait Blameable
 *
 * Automatically sets created_by, updated_by, and deleted_by from the authenticated user.
 * Stores the USER NAME (string), not ID, for better audit trail readability.
 *
 * Usage: Add `use Blameable;` to your model.
 *
 * Database Migration Requirements:
 * - created_by (string, nullable) -- Stores user NAME, not ID
 * - updated_by (string, nullable) -- Stores user NAME, not ID
 * - deleted_by (string, nullable) -- Stores user NAME, not ID (for soft deletes)
 *
 * Example Migration:
 * ```php
 * $table->string('created_by')->nullable();
 * $table->string('updated_by')->nullable();
 * $table->string('deleted_by')->nullable();
 * ```
 *
 * Why NAME instead of ID?
 * - Better audit trail: You can see who created/updated directly without joining users table
 * - Historical accuracy: Even if user is deleted, you still know who created the record
 * - No foreign key constraints needed
 * - Simpler queries for audit reports
 */
trait Blameable
{
    public static function bootBlameable()
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
                    $model->saveQuietly(); // Save without triggering events
                }
            }
        });
    }
}
