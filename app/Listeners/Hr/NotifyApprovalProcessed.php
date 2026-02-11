<?php

namespace App\Listeners\Hr;

use App\Events\Hr\ApprovalProcessed;
use App\Notifications\Hr\ApprovalProcessedNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyApprovalProcessed
{
    /**
     * Handle the event.
     */
    public function handle(ApprovalProcessed $event): void
    {
        try {
            $pegawai = $event->pegawai;
            
            if (!$pegawai) {
                // Try to get pegawai from approval
                $modelClass = $event->approval->model;
                $model = $modelClass::find($event->approval->model_id);
                $pegawai = $model?->pegawai;
            }

            if (!$pegawai) {
                Log::warning('Cannot find pegawai for approval processed notification', [
                    'approval_id' => $event->approval->riwayatapproval_id,
                    'model' => $event->approval->model,
                    'model_id' => $event->approval->model_id,
                ]);
                return;
            }

            // Get user account for the pegawai
            $pegawaiUser = $pegawai->user;

            if (!$pegawaiUser) {
                Log::warning('Pegawai has no user account for notification', [
                    'approval_id' => $event->approval->riwayatapproval_id,
                    'pegawai_id' => $pegawai->pegawai_id,
                ]);
                return;
            }

            // Send notification to the pegawai
            $pegawaiUser->notify(new ApprovalProcessedNotification(
                $event->approval, 
                $pegawai, 
                $event->processedBy, 
                $event->action
            ));

            Log::info('Approval processed notification sent', [
                'approval_id' => $event->approval->riwayatapproval_id,
                'pegawai_id' => $pegawai->pegawai_id,
                'user_id' => $pegawaiUser->id,
                'action' => $event->action,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send approval processed notification', [
                'approval_id' => $event->approval->riwayatapproval_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
