@extends('layouts.admin.app')
@section('title', $pageTitle)

@section('header')
<x-tabler.page-header :title="$pageTitle" pretitle="Penjaminan Mutu">
    <x-slot:actions>
        <x-tabler.button href="{{ route('pemutu.periode-kpis.create') }}" style="primary" icon="ti ti-plus" text="Tambah Periode" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <x-tabler.datatable
            id="periode-kpi-table"
            route="{{ route('pemutu.periode-kpis.data') }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                ['data' => 'periode', 'name' => 'nama', 'title' => 'Periode'],
                ['data' => 'semester', 'name' => 'semester', 'title' => 'Semester', 'width' => '10%'],
                ['data' => 'tahun', 'name' => 'tahun', 'title' => 'Tahun', 'width' => '8%'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
            ]"
        />
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).on('click', '.activate-periode', function() {
    const url = $(this).data('url');
    Swal.fire({
        title: 'Aktifkan Periode?',
        text: 'Periode lain akan otomatis dinonaktifkan',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Aktifkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.fire('Berhasil!', response.message, 'success');
                    $('#periode-kpi-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Gagal!', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                }
            });
        }
    });
});
</script>
@endpush
