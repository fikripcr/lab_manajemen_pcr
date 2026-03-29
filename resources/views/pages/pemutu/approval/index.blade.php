@extends('layouts.tabler.app')

@section('title', 'Approval Dokumen')

@section('header')
<x-tabler.page-header title="Approval Dokumen" pretitle="Pemutu">
</x-tabler.page-header>
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <x-tabler.card>
            <x-tabler.card-header title="Daftar Approval Dokumen">
                <div class="d-flex gap-2">
                    <x-tabler.datatable-page-length dataTableId="table-approval" />
                    <x-tabler.datatable-search dataTableId="table-approval" />
                </div>
            </x-tabler.card-header>
            
            <div class="table-responsive">
                <x-tabler.datatable
                    id="table-approval"
                    route="{{ route('pemutu.approval.index') }}"
                    :columns="[
                        ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Masuk', 'width' => '15%'],
                        ['data' => 'tipe_approval', 'name' => 'model', 'title' => 'Tipe Dokumen', 'width' => '15%'],
                        ['data' => 'dokumen_kode', 'name' => 'dokumen_kode', 'title' => 'Kode', 'width' => '15%', 'orderable' => false, 'searchable' => false],
                        ['data' => 'dokumen_judul', 'name' => 'dokumen_judul', 'title' => 'Judul Dokumen', 'orderable' => false, 'searchable' => false],
                        ['data' => 'status_badge', 'name' => 'status', 'title' => 'Status', 'width' => '10%', 'class' => 'text-center'],
                        ['data' => 'oleh', 'name' => 'pejabat', 'title' => 'Oleh', 'width' => '15%'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false]
                    ]"
                />
            </div>
        </x-tabler.card>
    </div>
</div>
@endsection
