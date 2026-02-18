@extends('layouts.admin.app')

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
                    <x-tabler.button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-pic" icon="ti ti-plus" text="Tambah PIC" />
                </div>
            </div>
            <div class="card-table">
                <x-tabler.datatable-client
                    id="table-pic"
                    :columns="[
                        ['name' => 'Nama Pegawai'],
                        ['name' => 'Aksi', 'className' => 'w-10']
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
                                    data-url="{{ route('eoffice.jenis-layanan.destroy-pic', $pic->hashid) }}" 
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
                    <x-tabler.button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-isian" icon="ti ti-plus" text="Tambah Field" />
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
                            <tr data-id="{{ $isian->hashid }}">
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
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-primary edit-rule" title="Set Rule Validasi" icon="ti ti-shield-check" />
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-info edit-info" title="Set Info Tambahan" icon="ti ti-info-circle" />
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete" 
                                            data-url="{{ route('eoffice.jenis-layanan.destroy-isian', $isian->hashid) }}" 
                                            data-title="Hapus Field?" data-text="Field ini akan dihapus dari form layanan." icon="ti ti-trash" />
                                    </div>
                                    <input type="hidden" class="isian-rule" value="{{ $isian->rule }}">
                                    <input type="hidden" class="isian-info" value="{{ $isian->info_tambahan }}">
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
                    <x-tabler.button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-disposisi" icon="ti ti-plus" text="Tambah Disposisi" />
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
                            <tr data-id="{{ $d->hashid }}">
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
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-primary edit-disposisi" title="Edit Detail Info" icon="ti ti-pencil" />
                                        <x-tabler.button type="button" class="btn-icon btn-ghost-danger ajax-delete"
                                            data-url="{{ route('eoffice.jenis-layanan.disposisi.destroy', [$layanan->jenislayanan_id, $d->jldisposisi_id]) }}"
                                            data-title="Hapus Disposisi?" data-text="Disposisi akan dihapus dan urutan otomatis disesuaikan." icon="ti ti-trash" />
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
                    <x-tabler.button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-periode" icon="ti ti-plus" text="Tambah Periode" />
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
                        ['name' => 'Aksi', 'className' => 'w-10']
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
                                    data-url="{{ route('eoffice.jenis-layanan.periode.destroy', [$layanan->hashid, $p->hashid]) }}"
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

@push('modals')

<!-- Modal Add PIC -->
<x-tabler.form-modal
    id="modal-add-pic"
    title="Tambah PIC Layanan"
    route="{{ route('eoffice.jenis-layanan.store-pic', $layanan->hashid) }}"
    method="POST"
    submitText="Tambah PIC"
>
    <div class="mb-3">
        <x-tabler.form-select name="user_id" label="Pilih Pegawai" class="select2" required="true">
            <option value="">Pilih Pegawai</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </x-tabler.form-select>
    </div>
</x-tabler.form-modal>

<!-- Modal Add Isian -->
<x-tabler.form-modal
    id="modal-add-isian"
    title="Tambah Isian Form"
    route="{{ route('eoffice.jenis-layanan.store-isian', $layanan->hashid) }}"
    method="POST"
    submitText="Tambah Field"
>
    <div class="row">
        <div class="col-md-9 mb-3">
            <x-tabler.form-select name="kategoriisian_id" label="Pilih Isian Master" class="select2" required="true">
                <option value="">Pilih Isian</option>
                @foreach($kategoriIsians as $ki)
                    <option value="{{ $ki->kategoriisian_id }}">{{ $ki->nama_isian }} ({{ $ki->type }})</option>
                @endforeach
            </x-tabler.form-select>
        </div>
        <div class="col-md-3 mb-3">
            <x-tabler.form-input type="number" name="seq" label="No Urut" value="{{ $layanan->isians->max('seq') + 1 }}" required="true" />
        </div>
    </div>
    <div class="mb-3">
        <label class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_required" value="1" checked>
            <span class="form-check-label">Wajib Diisi (Required)</span>
        </label>
    </div>
</x-tabler.form-modal>

<!-- Modal Add Disposisi -->
<x-tabler.form-modal
    id="modal-add-disposisi"
    title="Tambah Alur Disposisi"
    route="{{ route('eoffice.jenis-layanan.disposisi.store', $layanan->hashid) }}"
    method="POST"
    submitText="Tambah Disposisi"
>
    <div class="mb-3">
        <x-tabler.form-select name="model" label="Model" required="true">
            <option value="">Pilih Model</option>
            <option value="Posisi">Posisi</option>
            <option value="JabatanStruktural">Jabatan Struktural</option>
            <option value="Lainnya">Lainnya (Manual)</option>
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="value" label="Value / Nama Tujuan" placeholder="Contoh: Kaprodi, Wadek, atau nama khusus" required="true" />
    </div>
        <x-tabler.form-textarea name="keterangan" label="Keterangan" rows="2" placeholder="Keterangan disposisi (opsional)" />
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="number" name="batas_pengerjaan" label="Batas Pengerjaan (Hari)" value="0" min="0" />
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" name="is_notify_email" value="1" checked>
                <span class="form-check-label">Kirim Notifikasi Email</span>
            </label>
        </div>
    </div>
</x-tabler.form-modal>

<!-- Modal Add Periode -->
<x-tabler.form-modal
    id="modal-add-periode"
    title="Tambah Periode Pengajuan"
    route="{{ route('eoffice.jenis-layanan.periode.store', $layanan->hashid) }}"
    method="POST"
    submitText="Tambah Periode"
>
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_mulai" label="Tanggal Mulai" required="true" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input type="date" name="tgl_selesai" label="Tanggal Selesai" required="true" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <x-tabler.form-input name="tahun_ajaran" label="Tahun Ajaran" placeholder="Contoh: 2025/2026" />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-select name="semester" label="Semester">
                <option value="">Pilih Semester</option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </x-tabler.form-select>
        </div>
    </div>
</x-tabler.form-modal>

<!-- Modal Edit Disposisi Info -->
<x-tabler.form-modal
    id="modal-edit-disposisi"
    title="Edit Detail Disposisi"
    id_form="form-edit-disposisi"
    submitText="Simpan Perubahan"
>
    <div class="mb-3">
        <x-tabler.form-input name="text" label="Teks Display / Alias" placeholder="Contoh: Pilih Kaprodi" required="true" />
    </div>
        <x-tabler.form-textarea name="keterangan" label="Keterangan Tambahan" rows="2" placeholder="Muncul di bawah label input" />
    <div class="mb-3">
        <x-tabler.form-input type="number" name="batas_pengerjaan" label="Batas Pengerjaan (Hari)" min="0" />
    </div>
</x-tabler.form-modal>

<!-- Modal Set Rule -->
<x-tabler.form-modal
    id="modal-set-rule"
    title="Set Rule Validasi (Laravel Style)"
    id_form="form-set-rule"
    submitText="Simpan Rule"
>
    <div class="mb-3">
        <x-tabler.form-input name="rule" label="Validation Rules" placeholder="mimes:pdf|max:2048" />
        <div class="form-text">Contoh: <code>mimes:pdf,doc,docx|max:5120</code> atau <code>numeric|min:1</code></div>
    </div>
</x-tabler.form-modal>

<!-- Modal Set Info -->
<x-tabler.form-modal
    id="modal-set-info"
    title="Set Keterangan Isian"
    id_form="form-set-info"
    submitText="Simpan Keterangan"
>
    <x-tabler.form-textarea name="info_tambahan" label="Keterangan / Instruksi" rows="4" placeholder="Muncul di bawah label input field..." />
</x-tabler.form-modal>

@endpush

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

            axios.put(`{{ url('eoffice/jenis-layanan/' . $layanan->hashid . '/disposisi') }}/${id}/notify`, { is_notify_email: val })
                .then(res => {
                    toastr.success(res.data.message);
                })
                .catch(err => {
                    toastr.error('Gagal memperbarui notifikasi');
                });
        });

        // Modals Isian
        $('.edit-rule').on('click', function() {
            let tr = $(this).closest('tr');
            let id = tr.data('id');
            let rule = tr.find('.isian-rule').val();

            $('#form-set-rule').attr('action', `{{ url('eoffice/jenis-layanan/isian') }}/${id}/rule`);
            $('#form-set-rule [name="rule"]').val(rule);
            $('#modal-set-rule').modal('show');
        });

        $('.edit-info').on('click', function() {
            let tr = $(this).closest('tr');
            let id = tr.data('id');
            let info = tr.find('.isian-info').val();

            $('#form-set-info').attr('action', `{{ url('eoffice/jenis-layanan/isian') }}/${id}/info`);
            $('#form-set-info [name="info_tambahan"]').val(info);
            $('#modal-set-info').modal('show');
        });

        // Modal Disposisi
        $('.edit-disposisi').on('click', function() {
            let tr = $(this).closest('tr');
            let id = tr.data('id');

            axios.get(`{{ url('eoffice/jenis-layanan/' . $layanan->hashid . '/disposisi') }}/${id}/data`)
                .then(res => {
                    let d = res.data;
                    $('#form-edit-disposisi').attr('action', `{{ url('eoffice/jenis-layanan/' . $layanan->hashid . '/disposisi') }}/${id}`);
                    $('#form-edit-disposisi [name="text"]').val(d.text);
                    $('#form-edit-disposisi [name="keterangan"]').val(d.keterangan);
                    $('#form-edit-disposisi [name="batas_pengerjaan"]').val(d.batas_pengerjaan);
                    $('#modal-edit-disposisi').modal('show');
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
                    axios.post(`/eoffice/jenis-layanan/{{ $layanan->hashid }}/isian/seq`, {
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
                        let url = `/eoffice/jenis-layanan/{{ $layanan->hashid }}/disposisi/${id}/seq`;

                        axios.post(url, { seq: index + 1 });
                    });
                    toastr.success('Urutan disposisi diperbarui');
                }
            });
        }
    });

    document.addEventListener('form-success', function(e) {
        location.reload();
    });
</script>
@endpush
