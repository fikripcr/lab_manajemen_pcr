<?php

namespace App\View\Components\Hr;

use Illuminate\View\Component;
use Illuminate\View\View;

class PendingApprovalWidget extends Component
{
    public $pendingCount;
    public $recentApprovals;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->loadData();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string
    {
        return view('components.hr.pending-approval-widget');
    }

    /**
     * Load pending approval data
     */
    private function loadData(): void
    {
        $this->pendingCount = \App\Models\Hr\RiwayatApproval::where('status', 'Pending')->count();
        
        $this->recentApprovals = \App\Models\Hr\RiwayatApproval::with(['pegawai'])
            ->where('status', 'Pending')
            ->latest()
            ->take(5)
            ->get();
    }
}
