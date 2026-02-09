<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $pageTitle }}</h3>
        <div class="card-actions">
            <a href="{{ route('pemutu.indikators.create') }}"
               class="btn btn-primary btn-sm ajax-modal-btn"
               data-modal-title="Tambah Indikator Baru">
                <i class="ti ti-plus"></i> Tambah Indikator
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4 class="alert-title">Info</h4>
            <div>Menampilkan semua indikator yang terkait dengan dokumen {{ $dokumen->judul }} melalui sub dokumen.</div>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter datatable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Indikator</th>
                        <th>Target</th>
                        <th>Jenis</th>
                        <th>Periode</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($indicators as $indicator)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $indicator->no_indikator }}</strong><br>
                            <small class="text-muted">{{ strlen($indicator->indikator) > 100 ? substr($indicator->indikator, 0, 100) . '...' : $indicator->indikator }}</small>
                        </td>
                        <td>{{ $indicator->target ?? '-' }}</td>
                        <td>
                            <span class="badge bg-blue-lt">{{ $indicator->jenis_indikator ?? '-' }}</span>
                        </td>
                        <td>
                            @if($indicator->periode_mulai && $indicator->periode_selesai)
                                {{ \Carbon\Carbon::parse($indicator->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($indicator->periode_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="btn-list flex-nowrap">
                                <a href="{{ route('pemutu.indikators.edit', $indicator->indikator_id) }}"
                                   class="btn btn-sm btn-icon btn-ghost-primary ajax-modal-btn"
                                   data-modal-title="Edit Indikator: {{ $indicator->no_indikator }}"
                                   title="Edit">
                                    <i class="ti ti-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty">
                                <div class="empty-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="9" y1="10" x2="9.01" y2="10" /><line x1="15" y1="10" x2="15.01" y2="10" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>
                                </div>
                                <p class="empty-title">Tidak ada indikator ditemukan</p>
                                <p class="empty-subtitle text-muted">
                                    Belum ada indikator yang terkait dengan dokumen ini.
                                </p>
                                <div class="empty-action">
                                    <a href="{{ route('pemutu.indikators.create') }}"
                                       class="btn btn-primary ajax-modal-btn"
                                       data-modal-title="Tambah Indikator Baru">
                                        <i class="ti ti-plus"></i> Tambah Indikator
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>