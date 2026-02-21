<?php
namespace App\View\Components\Hr;

use App\Models\Hr\RiwayatApproval;
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
    public function render(): View | string
    {
        return view('components.hr.pending-approval-widget');
    }

    /**
     * Load pending approval data
     */
    private function loadData(): void
    {
        $this->pendingCount = RiwayatApproval::where('status', 'Pending')->count();

        $this->recentApprovals = RiwayatApproval::with(['pegawai'])
            ->where('status', 'Pending')
            ->latest()
            ->take(5)
            ->get();
    }
}
