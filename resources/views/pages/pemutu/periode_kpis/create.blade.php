@extends(request()->ajax() ? 'layouts.blank' : 'layouts.admin.app')
@section('title', $pageTitle)

<x-tabler.form-modal :title="$pageTitle" route="{{ route('pemutu.periode-kpis.store') }}" method="POST">
    <x-tabler.form-input name="nama" label="Nama Periode" placeholder="Contoh: Semester Ganjil 2024/2025" required />
    <x-tabler.form-select name="semester" label="Semester" :options="['Ganjil' => 'Ganjil', 'Genap' => 'Genap']" required />
    <x-tabler.form-input name="tahun_akademik" label="Tahun Akademik" placeholder="Contoh: 2024/2025" required />
    <x-tabler.form-input type="number" name="tahun" label="Tahun" placeholder="Contoh: 2024" required />
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" required />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" required />
        </div>
    </div>
</x-tabler.form-modal>
