<div class="card-header">
    <h3 class="card-title">
        {{ $unit->name }}
        <span class="badge bg-primary-lt ms-2">{{ ucfirst(str_replace('_', ' ', $unit->type)) }}</span>
    </h3>
</div>
<div class="card-body">
    {{-- Current Assignments --}}
    <h4 class="mb-3">Pegawai yang Ditugaskan</h4>
    @if($assignments->count() > 0)
    <div class="table-responsive">
        <table class="table table-vcenter table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tgl Mulai</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $asn)
                <tr>
                    <td>
                        <a href="{{ route('hr.pegawai.show', $asn->pegawai_id) }}" target="_blank">
                            {{ $asn->pegawai->nama ?? '-' }}
                        </a>
                    </td>
                    <td>{{ $asn->tgl_mulai?->format('d M Y') }}</td>
                    <td>
                        @if($asn->is_active)
                            <span class="badge bg-success text-white">Aktif</span>
                        @else
                            <span class="badge bg-secondary text-white">Selesai</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-ghost-danger btn-remove-assignment" 
                                data-url="{{ route('hr.pegawai.penugasan.destroy', [$asn->pegawai_id, $asn->riwayatpenugasan_id]) }}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-muted mb-4">Belum ada pegawai ditugaskan ke unit ini.</div>
    @endif

    <hr class="my-4">

    {{-- Add New Assignment --}}
    <h4 class="mb-3">Tambah Penugasan Baru</h4>
    <form id="form-assign-pegawai" action="{{ route('hr.pegawai.mass-penugasan.assign') }}" method="POST">
        @csrf
        <input type="hidden" name="org_unit_id" value="{{ $unit->org_unit_id }}">
        
        <div class="row g-3">
            <div class="col-md-6">
                <x-tabler.form-select class="select2-ajax" name="pegawai_id" label="Pilih Pegawai" required="true"
                        data-ajax-url="{{ route('hr.pegawai.select2-search') }}"
                        data-placeholder="Cari nama pegawai..." />
            </div>
            <div class="col-md-3">
                <x-tabler.form-input type="date" name="tgl_mulai" label="Tanggal Mulai" value="{{ date('Y-m-d') }}" required="true" />
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <x-tabler.form-input name="no_sk" label="Nomor SK" placeholder="SK/..." />
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Tambah Penugasan
            </button>
        </div>
    </form>
</div>
