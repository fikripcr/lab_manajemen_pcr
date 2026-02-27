@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="{{ $layanan->nama_layanan }}" pretitle="Manage Konfigurasi Layanan">
    <x-slot:actions>
        <x-tabler.button type="button" icon="ti ti-arrow-left" text="Kembali" class="btn-link"
            onclick="window.location.href='{{ route('eoffice.jenis-layanan.index') }}'" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <!-- PIC Section -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manajemen PIC (Petugas)</h3>
                <div class="card-actions">
                    <x-tabler.button type="button" class="btn-primary btn-sm ajax-modal-btn"
                        data-url="{{ route('eoffice.jenis-layanan.create-pic', $layanan->hashid) }}"
                        icon="ti ti-plus" text="Tambah PIC" />
                </div>
            </div>
            <div class="card-table">
                <x-tabler.datatable-client
                    id="table-pic"
                    :columns="[
                        ['name' => 'Nama Pegawai'],
                        ['name' => 'Aksi', 'class' => 'w-10']
                    ]"
                >
                    @forelse($layanan->pics as $pic)
                        <tr>
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar me-2" style="background-image: url('{{ $pic->user->avatar_url ?? '' }}')"></span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium">{{ $pic->user->name }}</div>
                                        <div class="text-secondary">{{ $pic->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete"
                                    data-url="{{ route('eoffice.jenis-layanan.destroy-pic', $pic->encrypted_jlpic_id) }}"
                                    data-title="Hapus PIC?" data-text="Pegawai ini tidak lagi menjadi PIC untuk layanan ini." icon="ti ti-trash" />
                            </td>
                        </tr>
                    @empty
                        {{-- Handled by component --}}
                    @endforelse
                </x-tabler.datatable-client>

                @if($layanan->pics->isEmpty())
                    <div class="text-center text-muted p-3">Belum ada PIC yang ditugaskan.</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Fields Section -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Isian (Fields)</h3>
                <div class="card-actions">
                    <x-tabler.button type="button" class="btn-primary btn-sm ajax-modal-btn"
                        data-url="{{ route('eoffice.jenis-layanan.create-isian', $layanan->hashid) }}"
                        icon="ti ti-plus" text="Tambah Field" />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table" id="tbl-isian">
                    <thead>
                        <tr>
                            <th width="5%"><i class="ti ti-arrows-sort"></i></th>
                            <th>Field / Type</th>
                            <th width="15%">Fill By</th>
                            <th width="8%">Req</th>
                            <th width="8%">Valid</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="sortable-isian">
                        @forelse($layanan->isians->sortBy('seq') as $isian)
                            <tr data-id="{{ $isian->encrypted_jlisian_id }}">
                                <td class="handle cursor-move"><i class="ti ti-drag-drop text-muted"></i></td>
                                <td>
                                    <div class="fw-bold text-wrap">{{ $isian->nama_isian }}</div>
                                    <div class="text-muted small">{{ function_exists('jenisIsian') ? jenisIsian($isian->type) : $isian->type }}</div>
                                </td>
                                <td>
                                    <x-tabler.form-select class="form-select-sm isian-toggle" name="fill_by" data-field="fill_by" class="mb-0">
                                        <option value="Pemohon" {{ $isian->fill_by == 'Pemohon' ? 'selected' : '' }}>Pemohon</option>
                                        <option value="Disposisi 1" {{ $isian->fill_by == 'Disposisi 1' ? 'selected' : '' }}>Disp 1</option>
                                        <option value="Disposisi 2" {{ $isian->fill_by == 'Disposisi 2' ? 'selected' : '' }}>Disp 2</option>
                                        <option value="Sistem" {{ $isian->fill_by == 'Sistem' ? 'selected' : '' }}>Sistem</option>
                                    </x-tabler.form-select>
                                </td>
                                <td class="text-center">
                                    <label class="form-check form-check-single form-switch">
                                        <input class="form-check-input isian-toggle" type="checkbox" data-field="is_required" value="1" {{ $isian->is_required ? 'checked' : '' }}>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <label class="form-check form-check-single form-switch">
                                        <input class="form-check-input isian-toggle" type="checkbox" data-field="is_show_on_validasi" value="1" {{ $isian->is_show_on_validasi ? 'checked' : '' }}>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn"
                                            data-url="{{ route('eoffice.jenis-layanan.edit-isian-rule', $isian->encrypted_jlisian_id) }}"
                                            title="Set Rule Validasi" icon="ti ti-shield-check" />
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-info ajax-modal-btn"
                                            data-url="{{ route('eoffice.jenis-layanan.edit-isian-info', $isian->encrypted_jlisian_id) }}"
                                            title="Set Info Tambahan" icon="ti ti-info-circle" />
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete"
                                            data-url="{{ route('eoffice.jenis-layanan.destroy-isian', $isian->encrypted_jlisian_id) }}"
                                            data-title="Hapus Field?" data-text="Field ini akan dihapus dari form layanan." icon="ti ti-trash" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="6" class="text-center text-muted">Belum ada field yang dikonfigurasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Disposisi Chain Section -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Alur Disposisi</h3>
                <div class="card-actions">
                    <x-tabler.button type="button" class="btn-primary btn-sm ajax-modal-btn"
                        data-url="{{ route('eoffice.jenis-layanan.disposisi.create', $layanan->hashid) }}"
                        icon="ti ti-plus" text="Tambah Disposisi" />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table" id="tbl-disposisi">
                    <thead>
                        <tr>
                            <th width="5%"><i class="ti ti-arrows-sort"></i></th>
                            <th>Tujuan / Model</th>
                            <th>Alias Detail</th>
                            <th width="8%">Notif</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="sortable-disposisi">
                        @forelse($layanan->disposisis->sortBy('seq') as $d)
                            <tr data-id="{{ $d->encrypted_jldisposisi_id }}">
                                <td class="handle cursor-move"><i class="ti ti-drag-drop text-muted"></i></td>
                                <td>
                                    <div class="fw-medium">{{ $d->value }}</div>
                                    <div class="text-muted small">{{ $d->model }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $d->text ?? '-' }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 150px;">{{ $d->keterangan ?? '-' }}</div>
                                </td>
                                <td class="text-center">
                                    <label class="form-check form-check-single form-switch">
                                        <input class="form-check-input disposisi-toggle-notif" type="checkbox" value="1" {{ $d->is_notify_email ? 'checked' : '' }}>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-primary ajax-modal-btn"
                                            data-url="{{ route('eoffice.jenis-layanan.disposisi.edit', [$layanan->hashid, $d->hashid]) }}"
                                            title="Edit Detail Info" icon="ti ti-pencil" />
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete"
                                            data-url="{{ route('eoffice.jenis-layanan.disposisi.destroy', [$layanan->encrypted_jenislayanan_id, $d->encrypted_jldisposisi_id]) }}"
                                            data-title="Hapus Disposisi?" data-text="Disposisi akan dihapus and urutan otomatis disesuaikan." icon="ti ti-trash" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="5" class="text-center text-muted">Belum ada alur disposisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Periode Section -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Periode Pengajuan</h3>
                <div class="card-actions">
                    <x-tabler.button type="button" class="btn-primary btn-sm ajax-modal-btn"
                        data-url="{{ route('eoffice.jenis-layanan.periode.create', $layanan->hashid) }}"
                        icon="ti ti-plus" text="Tambah Periode" />
                </div>
            </div>
            <div class="card-table">
                <x-tabler.datatable-client
                    id="table-periode"
                    :columns="[
                        ['name' => 'Periode'],
                        ['name' => 'Tahun Ajaran'],
                        ['name' => 'Semester'],
                        ['name' => 'Status'],
                        ['name' => 'Aksi', 'class' => 'w-10']
                    ]"
                >
                    @forelse($layanan->periodes as $p)
                        <tr>
                            <td>{{ $p->tgl_mulai->format('d M Y') }} - {{ $p->tgl_selesai->format('d M Y') }}</td>
                            <td>{{ $p->tahun_ajaran ?? '-' }}</td>
                            <td>{{ $p->semester ?? '-' }}</td>
                            <td>
                                @if(now()->between($p->tgl_mulai, $p->tgl_selesai))
                                    <span class="badge bg-green text-white">Aktif</span>
                                @elseif(now()->lt($p->tgl_mulai))
                                    <span class="badge bg-azure text-white">Mendatang</span>
                                @else
                                    <span class="badge bg-secondary text-white">Berakhir</span>
                                @endif
                            </td>
                            <td>
                                <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete"
                                    data-url="{{ route('eoffice.jenis-layanan.periode.destroy', [$layanan->encrypted_jenislayanan_id, $p->encrypted_jlperiode_id]) }}"
                                    data-title="Hapus Periode?" data-text="Periode ini akan dihapus." icon="ti ti-trash" />
                            </td>
                        </tr>
                    @empty
                        {{-- Handled by component --}}
                    @endforelse
                </x-tabler.datatable-client>

                @if($layanan->periodes->isEmpty())
                    <div class="text-center text-muted p-3">Belum ada periode pengajuan.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Isian fields
        $('.isian-toggle').on('change', function() {
            let tr = $(this).closest('tr');
            let id = tr.data('id');
            let field = $(this).data('field');
            let val = $(this).is(':checkbox') ? ($(this).is(':checked') ? 1 : 0) : $(this).val();

            let data = {};
            data[field] = val;

            axios.post(`{{ url('eoffice/jenis-layanan/isian') }}/${id}/toggle`, data)
                .then(res => {
                    toastr.success(res.data.message);
                })
                .catch(err => {
                    toastr.error('Gagal memperbarui field');
                });
        });

            // Toggle Disposisi Notify
            $('.disposisi-toggle-notif').on('change', function() {
                let tr = $(this).closest('tr');
                let id = tr.data('id');
                let val = $(this).is(':checked') ? 1 : 0;

                axios.put(`{{ url('eoffice/jenis-layanan/' . $layanan->encrypted_jenislayanan_id . '/disposisi') }}/${id}/notify`, { is_notify_email: val })
                .then(res => {
                    toastr.success(res.data.message);
                })
                .catch(err => {
                    toastr.error('Gagal memperbarui notifikasi');
                });
        });


        // Drag and Drop reordering
        if (typeof Sortable !== 'undefined') {
            new Sortable(document.querySelector('.sortable-isian'), {
                handle: '.handle',
                animation: 150,
                onEnd: function() {
                    let sequences = [];
                    $('.sortable-isian tr').each(function(index) {
                        sequences.push({ id: $(this).data('id'), seq: index + 1 });
                    });
                    axios.post(`/eoffice/jenis-layanan/{{ $layanan->encrypted_jenislayanan_id }}/isian/seq`, {
                        sequences: sequences
                    })
                    .then(res => toastr.success(res.data.message));
                }
            });

            new Sortable(document.querySelector('.sortable-disposisi'), {
                handle: '.handle',
                animation: 150,
                onEnd: function() {
                    $('.sortable-disposisi tr').each(function(index) {
                        let id = $(this).data('id');
                        let url = `/eoffice/jenis-layanan/{{ $layanan->encrypted_jenislayanan_id }}/disposisi/${id}/seq`;

                        axios.post(url, { seq: index + 1 });
                    });
                    toastr.success('Urutan disposisi diperbarui');
                }
            });
        }
    });

    document.addEventListener('ajax-form:success', function(e) {
        location.reload();
    });
</script>
@endpush
