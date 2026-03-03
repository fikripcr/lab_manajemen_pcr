@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Manajemen Personil" pretitle="Master Data">
        <x-slot:actions>
            <x-tabler.button :href="route('shared.personil.create')" class="d-none d-sm-inline-block ajax-modal-btn" icon="ti ti-plus" text="Tambah Personil" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
        <div class="card-header border-bottom py-3">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'table-personil'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'table-personil'" />
                </div>
                <div>
                    <x-tabler.datatable-filter :dataTableId="'table-personil'">
                        <div class="col-12">
                            <x-tabler.form-select id="filter-unit" name="org_unit_id" label="Filter Unit" placeholder="Semua Unit" class="mb-0">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->orgunit_id }}">{{ $unit->name_display ?? $unit->name }}</option>
                                @endforeach
                            </x-tabler.form-select>
                        </div>
                    </x-tabler.datatable-filter>
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.datatable
                id="table-personil"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'personil_id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'nama', 'name' => 'nama', 'title' => 'Nama'],
                    ['data' => 'nip', 'name' => 'nip', 'title' => 'NIP/NIK'],
                    ['data' => 'posisi', 'name' => 'posisi', 'title' => 'Posisi'],
                    ['data' => 'user_info', 'name' => 'user_info', 'title' => 'User Terkoneksi'],
                    ['data' => 'status_aktif', 'name' => 'status_aktif', 'title' => 'Status'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                ]"
                :route="route('shared.personil.data')"
            />
        </div>
        </div>
@endsection

@push('scripts')
<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.generate-user');
    if (!btn) return;
    e.preventDefault();

    const url = btn.dataset.url;

    showConfirmation(
        'Konfirmasi Pembuatan User',
        'Apakah Anda yakin ingin membuat user untuk personil ini?\n\nDefault password: password123',
        'Ya, Buat User'
    ).then((result) => {
        if (result.isConfirmed) {
            showLoadingMessage('Memproses...', 'Sedang membuat akun personil');

            axios.post(url)
                .then(function(response) {
                    if (response.data.success) {
                        showSuccessMessage('Berhasil!', response.data.message);
                        $('#table-personil').DataTable().ajax.reload();
                    } else {
                        showErrorMessage('Gagal!', response.data.message);
                    }
                })
                .catch(function(error) {
                    const message = error.response?.data?.message || 'Terjadi kesalahan saat membuat user.';
                    showErrorMessage('Error!', message);
                });
        }
    });
});
</script>
@endpush

