@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Management Mata Uji (CBT)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-action" data-title="Tambah Mata Uji" data-url="{{ route('cbt.mata-uji.create') }}">
                    <i class="ti ti-plus"></i> Tambah Mata Uji
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-mata-uji" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'nama_mata_uji', 'name' => 'nama_mata_uji', 'title' => 'Nama Mata Uji'],
                        ['data' => 'tipe', 'name' => 'tipe', 'title' => 'Tipe'],
                        ['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('cbt.mata-uji.paginate')"
                />
            </div>
        </div>
    </div>
</div>

<div class="modal modal-blur fade" id="modal-action" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="modal-content-body"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#modal-action').on('show.bs.modal', function(e) {
            var btn = $(e.relatedTarget);
            var url = btn.data('url');
            var title = btn.data('title');
            var modal = $(this);
            modal.find('.modal-title').text(title);
            modal.find('#modal-content-body').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            $.get(url, function(res) {
                modal.find('#modal-content-body').html(res);
            });
        });
    });
</script>
@endpush
