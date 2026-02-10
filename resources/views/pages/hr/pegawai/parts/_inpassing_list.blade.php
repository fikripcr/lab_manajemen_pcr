<div class="d-flex justify-content-between align-items-center mb-3 mt-3 px-3">
    <h3>Riwayat Inpassing</h3>
    <a href="#" class="btn btn-primary btn-sm ajax-modal-btn" data-url="{{ route('hr.pegawai.inpassing.create', $pegawai->encrypted_pegawai_id) }}" data-modal-title="Tambah Inpassing">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
        Tambah
    </a>
</div>
<div class="card mb-3 mx-3">
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-striped">
            <thead>
                <tr>
                    <th>Golongan</th>
                    <th>No SK</th>
                    <th>TMT</th>
                    <th>Masa Kerja</th>
                    <th>Gaji Pokok</th>
                    <th>File</th>
                    <th>Status Approval</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pegawai->historyInpassing as $item)
                <tr>
                    <td>{{ $item->golonganInpassing->golongan ?? '-' }} ({{ $item->golonganInpassing->nama_pangkat ?? '-' }})</td>
                    <td>
                        {{ $item->no_sk }}<br>
                        <small class="text-muted">{{ $item->tgl_sk ? $item->tgl_sk->format('d-m-Y') : '' }}</small>
                    </td>
                    <td>{{ $item->tmt ? $item->tmt->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->masa_kerja_tahun ?? 0 }} Tahun {{ $item->masa_kerja_bulan ?? 0 }} Bulan</td>
                     <td>Rp {{ number_format($item->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @if($item->file_sk)
                            <a href="{{ Storage::url($item->file_sk) }}" target="_blank" class="text-azure">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($pegawai->latest_riwayatinpassing_id == $item->riwayatinpassing_id)
                            <span class="badge bg-success">Aktif Saat Ini</span>
                        @else
                            <span class="badge bg-secondary">Riwayat</span>
                        @endif
                    </td>
                    <td>
                         <div class="btn-group">
                            <a href="#" class="btn btn-sm btn-outline-primary ajax-modal-btn" data-url="{{ route('hr.pegawai.inpassing.edit', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $item->riwayatinpassing_id]) }}" data-modal-title="Edit Inpassing">Edit</a>
                             <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="{{ route('hr.pegawai.inpassing.destroy', ['pegawai' => $pegawai->encrypted_pegawai_id, 'inpassing' => $item->riwayatinpassing_id]) }}" data-confirm-msg="Yakin ingin menghapus data inpassing ini?">Hapus</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Belum ada data inpassing</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
