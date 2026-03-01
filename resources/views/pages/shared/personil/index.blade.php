@extends('layouts.tabler.app')

@section('header')
    <x-tabler.page-header title="Manajemen Personil" pretitle="Master Data">
        <x-slot:actions>
            <x-tabler.button :href="route('shared.personil.create')" class="d-none d-sm-inline-block ajax-modal-btn" icon="ti ti-plus" text="Tambah Personil" />
        </x-slot:actions>
    </x-tabler.page-header>
@endsection

@section('content')
        <div class="card">
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

    if (!confirm('Apakah Anda yakin ingin membuat user untuk personil ini?\n\nDefault password: password123')) {
        return;
    }

    axios.post(url)
        .then(function(response) {
            if (response.data.success) {
                Swal.fire({ icon: 'success', title: 'Berhasil!', html: response.data.message });
                $('#table-personil').DataTable().ajax.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', html: response.data.message });
            }
        })
        .catch(function(error) {
            const message = error.response?.data?.message || 'Terjadi kesalahan saat membuat user.';
            Swal.fire({ icon: 'error', title: 'Error!', text: message });
        });
});
</script>
@endpush

