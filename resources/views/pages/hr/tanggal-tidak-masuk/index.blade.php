@extends('layouts.admin.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Tanggal Tidak Masuk</h2>
                <div class="text-muted mt-1">Manage National Holidays & Non-Working Days</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('hr.tanggal-tidak-masuk.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Add New Date(s)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body p-0">
                <x-tabler.flash-message />
                <x-tabler.datatable 
                    id="table-tanggal-tidak-masuk"
                    route="{{ route('hr.tanggal-tidak-masuk.index') }}"
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                        ['data' => 'tanggal', 'name' => 'tanggal', 'title' => 'Tanggal'],
                        ['data' => 'tahun', 'name' => 'tahun', 'title' => 'Tahun'],
                        ['data' => 'keterangan', 'name' => 'keterangan', 'title' => 'Keterangan'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false, 'className' => 'text-end'],
                    ]"
                />
            </div>
{{-- No custom script needed, handled by component --}}
@endsection
