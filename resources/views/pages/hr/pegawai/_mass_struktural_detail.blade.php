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
                            <span class="status status-success"><span class="status-dot status-dot-animated"></span> Aktif</span>
                        @else
                            <span class="status status-secondary"><span class="status-dot"></span> Selesai</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <x-tabler.button type="button" class="btn-sm btn-ghost-danger btn-remove-assignment" 
                                data-url="{{ route('hr.pegawai.struktural.destroy', [encryptId($asn->pegawai_id), $asn->encrypted_riwayatstruktural_id]) }}" icon="ti ti-trash" icon-only />
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

    {{-- Add New Struktural --}}
    <h4 class="mb-3">Tambah Struktural Baru</h4>
    <form id="form-assign-pegawai" action="{{ route('hr.pegawai.mass-struktural.assign') }}" method="POST">
        @csrf
        <input type="hidden" name="org_unit_id" value="{{ $unit->orgunit_id }}">
        
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
            <x-tabler.button type="submit" class="btn-primary" icon="ti ti-plus" text="Tambah Struktural" />
        </div>
    </form>
</div>
