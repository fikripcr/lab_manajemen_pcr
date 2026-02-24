@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Penjadwalan Ujian (CBT)" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Jadwal Ujian" data-url="{{ route('cbt.jadwal.create') }}" icon="ti ti-plus" text="Tambah Jadwal" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <x-tabler.datatable
                id="table-jadwal"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                    ['data' => 'kegiatan_paket', 'name' => 'nama_kegiatan', 'title' => 'Ujian & Paket', 'orderable' => false],
                    ['data' => 'token_info', 'name' => 'token_ujian', 'title' => 'Token', 'orderable' => false],
                    ['data' => 'waktu_status', 'name' => 'waktu_mulai', 'title' => 'Waktu & Status', 'orderable' => false],
                    ['data' => 'peserta', 'name' => 'peserta', 'title' => 'Peserta', 'orderable' => false, 'class' => 'text-center'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '150px', 'class' => 'text-center']
                ]"
                :url="route('cbt.jadwal.paginate')"
            />
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.btn-jadwal-action', function() {
        const btn = $(this);
        const url = btn.data('url');

        btn.prop('disabled', true);
        $.post(url, { _token: '{{ csrf_token() }}' }, function(res) {
            if (res.success || res.status === 'success') {
                toastr.success(res.message || 'Berhasil');
                $('#table-jadwal').DataTable().ajax.reload(null, false);
            } else {
                toastr.error(res.message || 'Terjadi kesalahan');
            }
        }).fail(function(xhr) {
            let errorMsg = 'Terjadi kesalahan';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            toastr.error(errorMsg);
        }).always(() => btn.prop('disabled', false));
    });
</script>
@endpush
