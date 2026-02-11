<?php

namespace App\Events\Hr;

use App\Models\Hr\RiwayatApproval;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApprovalRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $approval;
    public $pegawai;
    public $requestedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(RiwayatApproval $approval, $pegawai = null, $requestedBy = null)
    {
        $this->approval = $approval;
        $this->pegawai = $pegawai;
        $this->requestedBy = $requestedBy ?? auth()->user();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('hr.approvals'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'approval.requested';
    }
}
