<?php
namespace App\Listeners\Hr;

use App\Events\Hr\ApprovalRequested;
use App\Models\Shared\Pegawai;
use App\Notifications\Hr\ApprovalRequestNotification;
use Illuminate\Support\Facades\Log;

class NotifyApprovalRequested
{
    /**
     * Handle the event.
     */
    public function handle(ApprovalRequested $event): void
    {
        try {
            // Get admin users who can approve
            $adminUsers = $this->getApprovalUsers();

            if ($adminUsers->isEmpty()) {
                Log::warning('No approval users found for notification', [
                    'approval_id' => $event->approval->riwayatapproval_id,
                    'model'       => $event->approval->model,
                ]);
                return;
            }

            // Send notification to all approval users
            foreach ($adminUsers as $admin) {
                $admin->notify(new ApprovalRequestNotification($event->approval, $event->pegawai, $event->requestedBy));
            }

            Log::info('Approval request notifications sent', [
                'approval_id'    => $event->approval->riwayatapproval_id,
                'model'          => $event->approval->model,
                'notified_users' => $adminUsers->pluck('id')->toArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send approval request notifications', [
                'approval_id' => $event->approval->riwayatapproval_id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Get users who can approve requests
     */
    private function getApprovalUsers()
    {
        // Get users with HR approval permission
        return \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'hr-admin')
                ->orWhere('name', 'admin')
                ->orWhere('name', 'super-admin');
        })
            ->orWhereHas('permissions', function ($query) {
                $query->where('name', 'hr.approval.approve')
                    ->orWhere('name', 'hr.approval.manage');
            })
            ->active()
            ->get();
    }
}
