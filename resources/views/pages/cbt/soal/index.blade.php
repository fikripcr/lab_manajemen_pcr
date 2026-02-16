@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Bank Soal (CBT)</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('cbt.soal.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Tambah Soal
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Filter Mata Uji</label>
                        <select id="filter-mata-uji" class="form-select">
                            <option value="">Semua Mata Uji</option>
                            @foreach($mataUji as $mu)
                                <option value="{{ $mu->encrypted_id }}">{{ $mu->nama_mata_uji }} ({{ $mu->tipe }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-soal" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false],
                        ['data' => 'mata_uji.nama_mata_uji', 'name' => 'mataUji.nama_mata_uji', 'title' => 'Mata Uji'],
                        ['data' => 'tipe_soal', 'name' => 'tipe_soal', 'title' => 'Tipe'],
                        ['data' => 'konten_pertanyaan', 'name' => 'konten_pertanyaan', 'title' => 'Pertanyaan'],
                        ['data' => 'tingkat_kesulitan', 'name' => 'tingkat_kesulitan', 'title' => 'Kesulitan'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false]
                    ]"
                    :url="route('cbt.soal.paginate')"
                />
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#filter-mata-uji').on('change', function() {
            var val = $(this).val();
            var dt = window.LaravelDataTables["table-soal"];
            dt.ajax.url("{{ route('cbt.soal.paginate') }}?mata_uji_id=" + val).load();
        });
    });
</script>
@endpush
