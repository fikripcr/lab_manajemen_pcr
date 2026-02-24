@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Manajemen Personil" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button href="{{ route('shared.personil.create') }}" class="btn-primary d-none d-sm-inline-block" icon="ti ti-plus" text="Tambah Personil" />
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
                    :route="route('shared.personil.paginate')"
                />
            </div>
        </div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.generate-user', function(e) {
    e.preventDefault();

    const $button = $(this);
    const url = $button.data('url');

    if (!confirm('Apakah Anda yakin ingin membuat user untuk personil ini?\n\nDefault password: password123')) {
        return;
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: response.message
                });

                // Reload datatable
                $('#table-personil').DataTable().ajax.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: response.message
                });
            }
        },
        error: function(xhr) {
            let message = 'Terjadi kesalahan saat membuat user.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        }
    });
});
</script>
@endpush
