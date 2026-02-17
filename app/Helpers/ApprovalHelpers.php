<?php

if (! function_exists('getApprovalBadge')) {
    /**
     * Get approval status badge HTML
     */
    function getApprovalBadge($status, $text = null)
    {
        $badges = [
            'Pending'  => '<span class="badge bg-warning text-white">Menunggu Approval</span>',
            'Approved' => '<span class="badge bg-success text-white">Disetujui</span>',
            'Rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'Draft'    => '<span class="badge bg-secondary text-white">Draft</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary text-white">' . ($text ?? $status) . '</span>';
    }
}

if (! function_exists('getApprovalStatusText')) {
    /**
     * Get approval status text in Indonesian
     */
    function getApprovalStatusText($status)
    {
        $texts = [
            'Pending'  => 'Menunggu Approval',
            'Approved' => 'Disetujui',
            'Rejected' => 'Ditolak',
            'Draft'    => 'Draft',
        ];

        return $texts[$status] ?? $status;
    }
}

if (! function_exists('getApprovalIcon')) {
    /**
     * Get approval status icon
     */
    function getApprovalIcon($status)
    {
        $icons = [
            'Pending'  => 'ti ti-clock',
            'Approved' => 'ti ti-check',
            'Rejected' => 'ti ti-x',
            'Draft'    => 'ti ti-file-description',
        ];

        return $icons[$status] ?? 'ti ti-help';
    }
}

if (! function_exists('formatApprovalDate')) {
    /**
     * Format approval date for display
     */
    function formatApprovalDate($date)
    {
        if (! $date) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('d M Y H:i');
    }
}

if (! function_exists('getApprovalActionButtons')) {
    /**
     * Get approval action buttons HTML
     */
    function getApprovalActionButtons($approval, $size = 'sm')
    {
        $approveUrl = route('hr.approval.approve', $approval->riwayatapproval_id);
        $rejectUrl  = route('hr.approval.reject', $approval->riwayatapproval_id);

        return '
            <div class="btn-group" role="group">
                <button class="btn btn-success btn-' . $size . ' btn-approve" data-url="' . $approveUrl . '" title="Setujui">
                    <i class="ti ti-check"></i>
                </button>
                <button class="btn btn-danger btn-' . $size . ' btn-reject" data-url="' . $rejectUrl . '" title="Tolak">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        ';
    }
}
