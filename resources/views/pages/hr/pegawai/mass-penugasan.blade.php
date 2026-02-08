@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Penugasan Massal</h2>
                <div class="text-muted mt-1">Kelola penugasan pegawai berdasarkan struktur organisasi</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            {{-- Left Panel: OrgUnit Tree --}}
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Struktur Organisasi</h3>
                    </div>
                    <div class="card-body p-2">
                        <ul class="list-group list-group-flush" id="org-tree-list">
                            @foreach($units as $unit)
                                @include('pages.hr.pegawai._mass_tree_item', ['unit' => $unit])
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Right Panel: Assignment Form --}}
            <div class="col-lg-7">
                <div class="card" id="assignment-panel">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="ti ti-hand-click" style="font-size: 3rem;"></i>
                        <p class="mt-3">Pilih unit/jabatan di sebelah kiri untuk mengelola penugasan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Click handler for org unit items
    $(document).on('click', '.org-unit-item', function(e) {
        e.preventDefault();
        $('.org-unit-item').removeClass('active');
        $(this).addClass('active');
        
        const unitId = $(this).data('id');
        const url = "{{ route('hr.pegawai.mass-penugasan.detail', ':id') }}".replace(':id', unitId);
        
        $('#assignment-panel').html('<div class="card-body text-center py-5"><div class="spinner-border text-primary"></div></div>');
        
        axios.get(url)
            .then(res => $('#assignment-panel').html(res.data))
            .catch(err => {
                console.error(err);
                $('#assignment-panel').html('<div class="card-body text-danger">Gagal memuat data.</div>');
            });
    });

    // Assign pegawai form submission
    $(document).on('submit', '#form-assign-pegawai', function(e) {
        e.preventDefault();
        const form = $(this);
        
        axios.post(form.attr('action'), new FormData(this))
            .then(res => {
                if(res.data.success) {
                    toastr.success(res.data.message);
                    // Reload the detail panel
                    $('.org-unit-item.active').click();
                }
            })
            .catch(err => {
                console.error(err);
                toastr.error('Gagal menambahkan penugasan');
            });
    });

    // Remove assignment
    $(document).on('click', '.btn-remove-assignment', function(e) {
        e.preventDefault();
        if(!confirm('Hapus penugasan ini?')) return;
        
        const url = $(this).data('url');
        axios.delete(url)
            .then(res => {
                if(res.data.success) {
                    toastr.success(res.data.message);
                    $('.org-unit-item.active').click();
                }
            })
            .catch(err => {
                console.error(err);
                toastr.error('Gagal menghapus penugasan');
            });
    });
});
</script>
@endpush
