<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Lembur</h3>
        <div class="card-actions">
            <span class="badge bg-{{ $lembur->status_approval == 'approved' ? 'success' : ($lembur->status_approval == 'rejected' ? 'danger' : 'warning') }}">
                {{ ucfirst($lembur->status_approval) }}
            </span>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th width="200">Judul</th>
                <td>{{ $lembur->judul }}</td>
            </tr>
            <tr>
                <th>Pengusul</th>
                <td>{{ $lembur->pengusul?->latestDataDiri?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $lembur->tgl_pelaksanaan?->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Waktu</th>
                <td>{{ $lembur->jam_mulai }} - {{ $lembur->jam_selesai }} ({{ floor($lembur->durasi_menit / 60) }} jam {{ $lembur->durasi_menit % 60 }} menit)</td>
            </tr>
            <tr>
                <th>Uraian Pekerjaan</th>
                <td>{{ $lembur->uraian_pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <th>Alasan</th>
                <td>{{ $lembur->alasan ?? '-' }}</td>
            </tr>
        </table>

        <h4 class="mt-4">Pegawai yang Lembur</h4>
        <div class="card-table">
            <x-tabler.datatable-client
                id="table-pegawai-lembur"
                :columns="[
                    ['name' => 'No', 'className' => 'w-1'],
                    ['name' => 'Nama'],
                    ['name' => 'Catatan']
                ]"
            >
                @forelse($lembur->pegawais as $index => $pegawai)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex py-1 align-items-center">
                                <span class="avatar me-2" style="background-image: url({{ $pegawai->latestDataDiri->foto_url ?? '' }})"></span>
                                <div class="flex-fill">
                                    <div class="font-weight-medium">{{ $pegawai->latestDataDiri?->nama }}</div>
                                    <div class="text-secondary"><a href="#" class="text-reset">{{ $pegawai->latestDataDiri?->email }}</a></div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $pegawai->pivot->catatan ?? '-' }}</td>
                    </tr>
                @empty
                   {{-- Empty state handled by component --}}
                @endforelse
            </x-tabler.datatable-client>
        </div>

        <h4 class="mt-4">Riwayat Approval</h4>
        <div class="card-table mb-3">
             <x-tabler.datatable-client
                id="table-approval-history"
                :columns="[
                    ['name' => 'Tanggal'],
                    ['name' => 'Pejabat'],
                    ['name' => 'Status'],
                    ['name' => 'Keterangan']
                ]"
             >
                @forelse($lembur->approvals as $approval)
                    <tr>
                        <td>{{ $approval->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $approval->pejabat }}</td>
                        <td>
                            <span class="badge bg-{{ $approval->status == 'approved' ? 'success' : ($approval->status == 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($approval->status) }}
                            </span>
                        </td>
                        <td>{{ $approval->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    {{-- Empty state handled by component --}}
                @endforelse
            </x-tabler.datatable-client>
        </div>
        
        @if($lembur->status_approval == 'pending')
        <div class="card bg-muted-lt mt-3">
            <div class="card-body">
                <h4 class="card-title">Proses Approval</h4>
                <form class="ajax-form" action="{{ route('hr.lembur.approve', $lembur->hashid) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <x-tabler.form-input name="pejabat" label="Nama Pejabat" value="{{ Auth::check() ? Auth::user()->name : '' }}" required="true" placeholder="Nama Pejabat" />
                    </div>
                    <x-tabler.form-textarea name="keterangan" label="Keterangan / Komentar" rows="3" placeholder="Tambahkan catatan jika ada..." />
                    <div class="btn-list">
                        <x-tabler.button type="submit" name="status" value="approved" class="btn-success" onclick="this.form.status.value='approved'" icon="ti ti-check" text="Terima Pengajuan" />
                        <x-tabler.button type="submit" name="status" value="pending" class="btn-warning" onclick="this.form.status.value='pending'" icon="ti ti-clock" text="Tangguhkan" />
                        <x-tabler.button type="submit" name="status" value="rejected" class="btn-danger" onclick="this.form.status.value='rejected'" icon="ti ti-x" text="Tolak Pengajuan" />
                        
                        <!-- Hidden input for status default value handling -->
                        <input type="hidden" name="status" id="approval_status">
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
    // Simple script to handle button clicks setting the status value
    document.querySelectorAll('button[type="submit"][name="status"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default submission to set value first
            const form = this.closest('form');
            const statusInput = form.querySelector('input[name="status"]');
            statusInput.value = this.value;
            
            // Trigger submit via standard way or let ajax-form handle it
            // Since class is ajax-form, we just need to ensure the click passes the value.
            // Actually, for ajax-form usually simpler to let standard submit happen.
            // The onclick inline handler above might be enough or we remove e.preventDefault()
            
            // Let's rely on the onclick inline attribute I added in HTML: onclick="this.form.status.value='...'"
            // So we can remove this script block if standard form submission works.
            // However, with ajax-form listener, it might serialize the form. 
            // Hidden input is safest.
            form.requestSubmit(this);
        });
    });
</script>
