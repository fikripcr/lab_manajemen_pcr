@extends('layouts.tabler.app')
@section('title', 'Pengendalian — Periode ' . $periode->periode)

@section('header')
<x-tabler.page-header title="Pengendalian Indikator" pretitle="Periode {{ $periode->periode }}">
    <x-slot:actions>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </x-slot:actions>
</x-tabler.page-header>
@endsection

@section('content')
<div class="card">
    <div class="card-header border-bottom py-3">
        <h3 class="card-title">Daftar Indikator</h3>
    </div>
    <div class="card-body p-0">
        <x-tabler.datatable
            id="table-pengendalian"
            route="{{ route('pemutu.pengendalian.data', $periode->encrypted_periodespmi_id) }}"
            :columns="[
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'width' => '4%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'indikator_info', 'name' => 'indikator', 'title' => 'Indikator'],
                ['data' => 'analisis', 'name' => 'analisis', 'title' => 'Analisis', 'orderable' => false, 'searchable' => false],
                ['data' => 'status_ami', 'name' => 'status_ami', 'title' => 'AMI', 'width' => '8%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'status_pengend', 'name' => 'status_pengend', 'title' => 'Status', 'width' => '9%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'eisenhower_matrix', 'name' => 'eisenhower_matrix', 'title' => 'Matrix', 'width' => '9%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
                ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'width' => '7%', 'class' => 'text-center', 'orderable' => false, 'searchable' => false],
            ]"
        />
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Eisenhower Matrix — inline AJAX update (delegasi ke document karena DataTable render dinamis)
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('matrix-radio')) return;

        const radio      = e.target;
        const indorgunit = radio.dataset.indorgunit;
        const field      = radio.dataset.field;
        const value      = radio.value;

        axios.post('{{ url("pemutu/pengendalian/matrix") }}/' + indorgunit, {
            [field]: value,
            _token: document.querySelector('meta[name="csrf-token"]')?.content
        }).catch(err => {
            console.error('Gagal update matrix:', err);
        });
    });
});
</script>
@endpush
@endsection
