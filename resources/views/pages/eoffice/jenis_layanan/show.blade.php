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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-pic">
                        <i class="ti ti-plus"></i> Tambah PIC
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                    <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" 
                                        data-url="{{ route('eoffice.jenis-layanan.destroy-pic', $pic->pic_id) }}" 
                                        data-title="Hapus PIC?" data-text="Pegawai ini tidak lagi menjadi PIC untuk layanan ini.">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">Belum ada PIC yang ditugaskan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Fields Section -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Isian (Fields)</h3>
                <div class="card-actions">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-isian">
                        <i class="ti ti-plus"></i> Tambah Field
                    </button>
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
                            <tr data-id="{{ $isian->jenislayananisian_id }}">
                                <td class="handle cursor-move"><i class="ti ti-drag-drop text-muted"></i></td>
                                <td>
                                    <div class="fw-bold">{{ $isian->kategori->nama_isian }}</div>
                                    <div class="text-muted small">{{ jenisIsian($isian->kategori->type) }}</div>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm isian-toggle" data-field="fill_by">
                                        <option value="Pemohon" {{ $isian->fill_by == 'Pemohon' ? 'selected' : '' }}>Pemohon</option>
                                        <option value="Disposisi 1" {{ $isian->fill_by == 'Disposisi 1' ? 'selected' : '' }}>Disp 1</option>
                                        <option value="Disposisi 2" {{ $isian->fill_by == 'Disposisi 2' ? 'selected' : '' }}>Disp 2</option>
                                        <option value="Sistem" {{ $isian->fill_by == 'Sistem' ? 'selected' : '' }}>Sistem</option>
                                    </select>
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
                                        <button type="button" class="btn btn-icon btn-ghost-primary edit-rule" title="Set Rule Validasi">
                                            <i class="ti ti-shield-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-ghost-info edit-info" title="Set Info Tambahan">
                                            <i class="ti ti-info-circle"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete" 
                                            data-url="{{ route('eoffice.jenis-layanan.destroy-isian', $isian->jenislayananisian_id) }}" 
                                            data-title="Hapus Field?" data-text="Field ini akan dihapus dari form layanan.">
                                            <i class="ti ti-trash"></i>
                                        </button>
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-disposisi">
                        <i class="ti ti-plus"></i> Tambah Disposisi
                    </button>
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
                            <tr data-id="{{ $d->jldisposisi_id }}">
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
                                        <button type="button" class="btn btn-icon btn-ghost-primary edit-disposisi" title="Edit Detail Info">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete"
                                            data-url="{{ route('eoffice.jenis-layanan.disposisi.destroy', [$layanan->jenislayanan_id, $d->jldisposisi_id]) }}"
                                            data-title="Hapus Disposisi?" data-text="Disposisi akan dihapus dan urutan otomatis disesuaikan.">
                                            <i class="ti ti-trash"></i>
                                        </button>
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
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add-periode">
                        <i class="ti ti-plus"></i> Tambah Periode
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table" id="tbl-periode">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($layanan->periodes as $p)
                            <tr>
                                <td>{{ $p->tgl_mulai->format('d M Y') }} - {{ $p->tgl_selesai->format('d M Y') }}</td>
                                <td>{{ $p->tahun_ajaran ?? '-' }}</td>
                                <td>{{ $p->semester ?? '-' }}</td>
                                <td>
                                    @if(now()->between($p->tgl_mulai, $p->tgl_selesai))
                                        <span class="badge bg-green">Aktif</span>
                                    @elseif(now()->lt($p->tgl_mulai))
                                        <span class="badge bg-azure">Mendatang</span>
                                    @else
                                        <span class="badge bg-secondary">Berakhir</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-ghost-danger ajax-delete"
                                        data-url="{{ route('eoffice.jenis-layanan.periode.destroy', [$layanan->jenislayanan_id, $p->jlperiode_id]) }}"
                                        data-title="Hapus Periode?" data-text="Periode ini akan dihapus.">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada periode pengajuan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add PIC -->
<div class="modal modal-blur fade" id="modal-add-pic" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('eoffice.jenis-layanan.store-pic', $layanan->jenislayanan_id) }}" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah PIC Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Pilih Pegawai</label>
                        <select name="user_id" class="form-select select2" required>
                            <option value="">Pilih Pegawai</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Tambah PIC</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Isian -->
<div class="modal modal-blur fade" id="modal-add-isian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('eoffice.jenis-layanan.store-isian', $layanan->jenislayanan_id) }}" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Isian Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <label class="form-label required">Pilih Isian Master</label>
                            <select name="kategoriisian_id" class="form-select select2" required>
                                <option value="">Pilih Isian</option>
                                @foreach($kategoriIsians as $ki)
                                    <option value="{{ $ki->kategoriisian_id }}">{{ $ki->nama_isian }} ({{ $ki->type }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">No Urut</label>
                            <input type="number" name="seq" class="form-control" value="{{ $layanan->isians->max('seq') + 1 }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_required" value="1" checked>
                            <span class="form-check-label">Wajib Diisi (Required)</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Tambah Field</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Disposisi -->
<div class="modal modal-blur fade" id="modal-add-disposisi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('eoffice.jenis-layanan.disposisi.store', $layanan->jenislayanan_id) }}" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Alur Disposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Model</label>
                        <select name="model" class="form-select" required>
                            <option value="">Pilih Model</option>
                            <option value="Posisi">Posisi</option>
                            <option value="JabatanStruktural">Jabatan Struktural</option>
                            <option value="Lainnya">Lainnya (Manual)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Value / Nama Tujuan</label>
                        <input type="text" name="value" class="form-control" placeholder="Contoh: Kaprodi, Wadek, atau nama khusus" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan disposisi (opsional)"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Batas Pengerjaan (Hari)</label>
                            <input type="number" name="batas_pengerjaan" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_notify_email" value="1" checked>
                                <span class="form-check-label">Kirim Notifikasi Email</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Tambah Disposisi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Periode -->
<div class="modal modal-blur fade" id="modal-add-periode" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('eoffice.jenis-layanan.periode.store', $layanan->jenislayanan_id) }}" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Periode Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" name="tahun_ajaran" class="form-control" placeholder="Contoh: 2025/2026">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select">
                                <option value="">Pilih Semester</option>
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Tambah Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('form-success', function(e) {
        location.reload();
    });
</script>
<!-- Modal Edit Disposisi Info -->
<div class="modal modal-blur fade" id="modal-edit-disposisi" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-edit-disposisi" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Detail Disposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Teks Display / Alias</label>
                        <input type="text" name="text" class="form-control" placeholder="Contoh: Pilih Kaprodi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Muncul di bawah label input"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Batas Pengerjaan (Hari)</label>
                        <input type="number" name="batas_pengerjaan" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Set Rule -->
<div class="modal modal-blur fade" id="modal-set-rule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-set-rule" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Set Rule Validasi (Laravel Style)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Validation Rules</label>
                        <input type="text" name="rule" class="form-control" placeholder="mimes:pdf|max:2048">
                        <div class="form-text">Contoh: <code>mimes:pdf,doc,docx|max:5120</code> atau <code>numeric|min:1</code></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Simpan Rule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Set Info -->
<div class="modal modal-blur fade" id="modal-set-info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="form-set-info" method="POST" class="ajax-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Set Keterangan Isian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Keterangan / Instruksi</label>
                        <textarea name="info_tambahan" class="form-control" rows="4" placeholder="Muncul di bawah label input field..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary ms-auto">Simpan Keterangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

            axios.put(`{{ url('eoffice/jenis-layanan/' . $layanan->jenislayanan_id . '/disposisi') }}/${id}/notify`, { is_notify_email: val })
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

            axios.get(`{{ url('eoffice/jenis-layanan/' . $layanan->jenislayanan_id . '/disposisi') }}/${id}/data`)
                .then(res => {
                    let d = res.data;
                    $('#form-edit-disposisi').attr('action', `{{ url('eoffice/jenis-layanan/' . $layanan->jenislayanan_id . '/disposisi') }}/${id}`);
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
                    axios.post(`{{ route('eoffice.jenis-layanan.update-isian-seq') }}`, { sequences: sequences })
                        .then(res => toastr.success(res.data.message));
                }
            });

            new Sortable(document.querySelector('.sortable-disposisi'), {
                handle: '.handle',
                animation: 150,
                onEnd: function() {
                    $('.sortable-disposisi tr').each(function(index) {
                        let id = $(this).data('id');
                        axios.post(`{{ url('eoffice/jenis-layanan/' . $layanan->jenislayanan_id . '/disposisi') }}/${id}/seq`, { seq: index + 1 });
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
