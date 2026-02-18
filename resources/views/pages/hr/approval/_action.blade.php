<x-tabler.button type="button" class="btn-success btn-sm btn-approve" data-url="{{ route('hr.approval.approve', $row->riwayatapproval_id) }}" text="Setujui" />
<x-tabler.button type="button" class="btn-danger btn-sm btn-reject" data-url="{{ route('hr.approval.reject', $row->riwayatapproval_id) }}" text="Tolak" />
