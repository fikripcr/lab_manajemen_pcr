@extends(request()->ajax() ? 'layouts.blank' : 'layouts.admin.app')
@section('title', $pageTitle)

<x-tabler.form-modal :title="$pageTitle" route="{{ route('pemutu.periode-kpis.update', $periodeKpi->periode_kpi_id) }}" method="PUT">
    <x-tabler.form-input name="nama" label="Nama Periode" :value="$periodeKpi->nama" required />
    <x-tabler.form-select name="semester" label="Semester" :options="['Ganjil' => 'Ganjil', 'Genap' => 'Genap']" :value="$periodeKpi->semester" required />
    <x-tabler.form-input name="tahun_akademik" label="Tahun Akademik" :value="$periodeKpi->tahun_akademik" required />
    <x-tabler.form-input type="number" name="tahun" label="Tahun" :value="$periodeKpi->tahun" required />
    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_mulai" label="Tanggal Mulai" :value="$periodeKpi->tanggal_mulai->format('Y-m-d')" required />
        </div>
        <div class="col-md-6">
            <x-tabler.form-input type="date" name="tanggal_selesai" label="Tanggal Selesai" :value="$periodeKpi->tanggal_selesai->format('Y-m-d')" required />
        </div>
    </div>
</x-tabler.form-modal>
