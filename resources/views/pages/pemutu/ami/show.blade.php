@extends('layouts.tabler.app')
@section('title', 'AMI — ' . $periode->periode)

@section('header')
<x-tabler.page-header title="Daftar Indikator AMI" pretitle="Periode {{ $periode->periode }}">
    <x-slot:actions>
        <a href="{{ route('pemutu.ami.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header py-3" style="background: linear-gradient(90deg, #0ea5e9, #6366f1); color: white;">
        <h3 class="card-title text-white mb-0">
            <i class="ti ti-shield-check me-2"></i>
            AMI — {{ $periode->periode }}
            <span class="badge bg-white text-primary ms-2 fw-normal">{{ $periode->jenis }}</span>
        </h3>
    </div>
    <div class="card-body border-bottom">
        <p class="text-muted mb-0">Berikut adalah daftar indikator yang sudah mengisi Evaluasi Diri. Klik <strong>"Isi AMI"</strong> untuk melakukan audit dan penilaian.</p>
    </div>
    <div class="table-responsive">
        <x-tabler.datatable
            id="table-ami"
            route="{{ route('pemutu.ami.data', $periode->encrypted_periodespmi_id) }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '5%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'indikator_info', 'name' => 'indikator_info', 'title' => 'Indikator'],
                ['data' => 'status_ed', 'name' => 'status_ed', 'title' => 'Status ED', 'width' => '12%', 'class' => 'text-center', 'orderable' => false],
                ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'Status AMI', 'width' => '12%', 'class' => 'text-center', 'orderable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '10%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
            ]"
        />
    </div>
</div>
@endsection
