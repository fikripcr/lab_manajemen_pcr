@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Master Mahasiswa" pretitle="Master Data">
    <x-slot:actions>
        <x-tabler.button type="create" class="ajax-modal-btn" :modal-url="route('lab.mahasiswa.create')" modal-title="Tambah Mahasiswa" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-wrap gap-2">
                <div>
                    <x-tabler.datatable-page-length :dataTableId="'mahasiswa-table'" />
                </div>
                <div>
                    <x-tabler.datatable-search :dataTableId="'mahasiswa-table'" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <x-tabler.flash-message />

            <x-tabler.datatable
                id="mahasiswa-table" route="{{ route('lab.mahasiswa.data') }}" :columns="[
                [
                    'title' => 'NIM',
                    'data' => 'nim',
                    'name' => 'nim',
                    'class' => 'text-center',
                ],
                [
                    'title' => 'Nama Mahasiswa',
                    'data' => 'nama',
                    'name' => 'nama',
                ],
                [
                    'title' => 'Program Studi',
                    'data' => 'prodi_nama',
                    'name' => 'prodi_nama',
                ],
                [
                    'title' => 'User',
                    'data' => 'user_info',
                    'name' => 'user_info',
                ],
                [
                    'title' => 'Actions',
                    'class' => 'text-center',
                    'data' => 'action',
                    'name' => 'action',
                    'orderable' => false,
                    'searchable' => false,
                ],
            ]" />
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    window.jQuery(document).on('click', '.generate-user', function(e) {
        e.preventDefault();
        
        const $button = window.jQuery(this);
        const url = $button.data('url');
        
        if (!confirm('Apakah Anda yakin ingin membuat user untuk mahasiswa ini?\n\nDefault password: password123')) {
            return;
        }
        
        window.jQuery.ajax({
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
                    window.jQuery('#mahasiswa-table').DataTable().ajax.reload();
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
});
</script>
@endpush
