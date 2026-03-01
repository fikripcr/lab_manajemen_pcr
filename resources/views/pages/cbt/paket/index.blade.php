@extends('layouts.tabler.app')

@section('header')
<x-tabler.page-header title="Paket Ujian" pretitle="CBT">
    <x-slot:actions>
        <x-tabler.button type="button" class="btn-primary ajax-modal-btn" data-modal-target="#modalAction" data-modal-title="Tambah Paket Ujian" data-url="{{ route('cbt.paket.create') }}" icon="ti ti-plus" text="Tambah Paket" />
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
    <div class="card">
            <div class="card-body">
                <x-tabler.datatable 
                    id="table-paket" 
                    :columns="[
                        ['data' => 'DT_RowIndex', 'name' => 'id', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'class' => 'text-center'],
                        ['data' => 'nama_paket', 'name' => 'nama_paket', 'title' => 'Nama Paket'],
                        ['data' => 'tipe_paket', 'name' => 'tipe_paket', 'title' => 'Tipe'],
                        ['data' => 'total_soal', 'name' => 'total_soal', 'title' => 'Jml Soal'],
                        ['data' => 'total_durasi_menit', 'name' => 'total_durasi_menit', 'title' => 'Durasi (Menit)', 'class' => 'text-center'],
                        ['data' => 'pembuat.name', 'name' => 'pembuat.name', 'title' => 'Dibuat Oleh', 'class' => 'text-center'],
                        ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'class' => 'text-center']
                    ]"
                    :url="route('cbt.paket.data')"
                />
            </div>
        </div>
@endsection

