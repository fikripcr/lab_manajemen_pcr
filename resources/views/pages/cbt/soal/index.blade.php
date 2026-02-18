@extends('layouts.admin.app')

@section('header')
<x-tabler.page-header title="Bank Soal (CBT)" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button href="{{ route('cbt.soal.create') }}" class="btn-primary" icon="ti ti-plus" text="Tambah Soal" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')

<div class="page-body">
    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <x-tabler.form-select id="filter-mata-uji" label="Filter Mata Uji">
                            <option value="">Semua Mata Uji</option>
                            @foreach($mataUji as $mu)
                                <option value="{{ $mu->hashid }}">{{ $mu->nama_mata_uji }} ({{ $mu->tipe }})</option>
                            @endforeach
                        </x-tabler.form-select>
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

@push('scripts')
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
