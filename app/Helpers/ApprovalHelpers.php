<?php

if (! function_exists('getApprovalStatus')) {
    /**
     * Get approval status dot HTML
     */
    function getApprovalStatus($status, $text = null)
    {
        $colors = [
            'Pending'    => 'warning',
            'Tangguhkan' => 'info',
            'Approved'   => 'success',
            'Rejected'   => 'danger',
            'Draft'      => 'secondary',
            'pending'    => 'warning',
            'tangguhkan' => 'info',
            'approved'   => 'success',
            'rejected'   => 'danger',
        ];

        $color       = $colors[$status] ?? 'secondary';
        $displayText = getApprovalStatusText($status) ?: ($text ?? $status);

        return '<span class="status status-' . $color . '">
                    <span class="status-dot status-dot-animated"></span>
                    ' . $displayText . '
                </span>';
    }
}

if (! function_exists('getApprovalBadge')) {
    /**
     * Get approval status badge HTML
     */
    function getApprovalBadge($status, $text = null)
    {
        $badges = [
            'Pending'    => '<span class="badge bg-warning text-white">Menunggu Approval</span>',
            'Tangguhkan' => '<span class="badge bg-info text-white">Ditangguhkan</span>',
            'Approved'   => '<span class="badge bg-success text-white">Disetujui</span>',
            'Rejected'   => '<span class="badge bg-danger text-white">Ditolak</span>',
            'Draft'      => '<span class="badge bg-secondary text-white">Draft</span>',
            'pending'    => '<span class="badge bg-warning text-white">Menunggu Approval</span>',
            'tangguhkan' => '<span class="badge bg-info text-white">Ditangguhkan</span>',
            'approved'   => '<span class="badge bg-success text-white">Disetujui</span>',
            'rejected'   => '<span class="badge bg-danger text-white">Ditolak</span>',
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
            'Pending'    => 'Menunggu Approval',
            'Tangguhkan' => 'Ditangguhkan',
            'Approved'   => 'Disetujui',
            'Rejected'   => 'Ditolak',
            'Draft'      => 'Draft',
            'pending'    => 'Menunggu Approval',
            'tangguhkan' => 'Ditangguhkan',
            'approved'   => 'Disetujui',
            'rejected'   => 'Ditolak',
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
            'Pending'    => 'ti ti-clock',
            'Tangguhkan' => 'ti ti-clock-pause',
            'Approved'   => 'ti ti-check',
            'Rejected'   => 'ti ti-x',
            'Draft'      => 'ti ti-file-description',
            'pending'    => 'ti ti-clock',
            'tangguhkan' => 'ti ti-clock-pause',
            'approved'   => 'ti ti-check',
            'rejected'   => 'ti ti-x',
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
