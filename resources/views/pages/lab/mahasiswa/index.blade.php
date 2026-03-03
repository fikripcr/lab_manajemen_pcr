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
        
        showConfirmation(
            'Konfirmasi Pembuatan User',
            'Apakah Anda yakin ingin membuat user untuk mahasiswa ini?\n\nDefault password: password123',
            'Ya, Buat User'
        ).then((result) => {
            if (result.isConfirmed) {
                showLoadingMessage('Memproses...', 'Sedang membuat akun mahasiswa');
                
                axios.post(url)
                    .then(function(response) {
                        if (response.data.success) {
                            showSuccessMessage('Berhasil!', response.data.message);
                            
                            // Reload datatable
                            window.jQuery('#mahasiswa-table').DataTable().ajax.reload();
                        } else {
                            showErrorMessage('Gagal!', response.data.message);
                        }
                    })
                    .catch(function(error) {
                        let message = 'Terjadi kesalahan saat membuat user.';
                        if (error.response && error.response.data && error.response.data.message) {
                            message = error.response.data.message;
                        }
                        showErrorMessage('Error!', message);
                    });
            }
        });
    });
});
</script>
@endpush
