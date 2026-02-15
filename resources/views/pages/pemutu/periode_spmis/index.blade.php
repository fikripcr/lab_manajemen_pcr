@extends('layouts.admin.app')

@section('content')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-calendar-event me-2 text-primary"></i> {{ $pageTitle }}
                </h2>
                <div class="text-muted mt-1">Kelola siklus PPEPP (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan) dalam satu periode.</div>
            </div>
            <!-- Page title actions -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <x-tabler.button href="{{ route('pemutu.periode-spmis.create') }}" style="primary" icon="ti ti-plus" class="d-none d-sm-inline-block">
                        Tambah Periode
                    </x-tabler.button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-0">
                <x-tabler.datatable
                    id="table-periode-spmi"
                    route="{{ route('pemutu.periode-spmis.data') }}"
                    ajax-load="true"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center', 'width' => '5%'],
                        ['data' => 'periode', 'name' => 'periode', 'title' => 'Tahun', 'class' => 'font-weight-bold'],
                        ['data' => 'jenis_periode', 'name' => 'jenis_periode', 'title' => 'Jenis'],
                        ['data' => 'penetapan_awal', 'name' => 'penetapan_awal', 'title' => 'Penetapan'],
                        ['data' => 'ami_awal', 'name' => 'ami_awal', 'title' => 'AMI (Evaluasi)'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-end', 'width' => '15%']
                    ]"
                />
            </div>
        </div>
    </div>
</div>
@endsection
