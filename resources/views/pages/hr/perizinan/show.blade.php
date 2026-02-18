<x-tabler.form-modal
    title="Detail Pengajuan Izin"
    submitText=""
    submitIcon=""
>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <tr>
                <th width="30%" class="bg-light">Pegawai</th>
                <td>{{ $perizinan->pengusulPegawai?->latestDataDiri->inisial }} - {{ $perizinan->pengusulPegawai?->latestDataDiri->nama }}</td>
            </tr>
            <tr>
                <th class="bg-light">Jenis Izin</th>
                <td>{{ $perizinan->jenisIzin?->nama }} ({{ $perizinan->jenisIzin?->kategori }})</td>
            </tr>
            <tr>
                <th class="bg-light">Waktu / Tanggal</th>
                <td>
                    {{ $perizinan->tgl_awal?->format('d/m/Y') }} 
                    @if($perizinan->tgl_akhir && $perizinan->tgl_akhir != $perizinan->tgl_awal)
                        s/d {{ $perizinan->tgl_akhir?->format('d/m/Y') }}
                    @endif
                    @if($perizinan->jam_awal)
                        <br><small class="text-muted">Jam: {{ $perizinan->jam_awal }} - {{ $perizinan->jam_akhir }}</small>
                    @endif
                </td>
            </tr>
            <tr>
                <th class="bg-light">Keterangan</th>
                <td>{{ $perizinan->keterangan ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Pekerjaan Ditinggalkan</th>
                <td>{{ $perizinan->pekerjaan_ditinggalkan ?? '-' }}</td>
            </tr>
            <tr>
                <th class="bg-light">Alamat</th>
                <td>{{ $perizinan->alamat_izin ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="p-3">
        <h4 class="mb-3">Riwayat Approval</h4>
        <div class="list-group list-group-flush border">
            @forelse ($perizinan->approvalHistory as $history)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @php
                                $statusIcons = [
                                    'Approved' => 'ti-check text-success',
                                    'Rejected' => 'ti-x text-danger',
                                    'Pending' => 'ti-clock text-warning',
                                    'Draft' => 'ti-file-text text-secondary'
                                ];
                                $icon = $statusIcons[$history->status] ?? 'ti-info-circle';
                            @endphp
                            <i class="ti {{ $icon }} fs-3"></i>
                        </div>
                        <div class="col">
                            <div class="fw-bold">{{ $history->status }}</div>
                            <div class="text-muted small">
                                @if($history->pejabat)
                                    Oleh: {{ $history->pejabat }} ({{ $history->jenis_jabatan }})
                                @else
                                    Dibuat oleh System
                                @endif
                                <br>
                                {{ $history->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($history->keterangan)
                                <div class="mt-1 p-2 bg-light rounded small">
                                    "{{ $history->keterangan }}"
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="list-group-item text-center text-muted">Belum ada riwayat approval.</div>
            @endforelse
        </div>
    </div>
</x-tabler.form-modal>
