@php
    $item = $periodeKpi ?? new \App\Models\Pemutu\PeriodeKpi();
    $route = $item->exists 
        ? route('pemutu.periode-kpis.update', $item->encrypted_periode_kpi_id) 
        : route('pemutu.periode-kpis.store');
    $method = $item->exists ? 'PUT' : 'POST';
    $title = $item->exists ? 'Edit Periode KPI' : 'Tambah Periode KPI';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    submitText="Simpan"
    submitIcon="ti-device-floppy"
>
    <div class="row">
        <div class="col-md-12 mb-3">
            <x-tabler.form-input 
                name="nama" 
                label="Nama Periode" 
                placeholder="Contoh: Semester Ganjil 2024/2025" 
                value="{{ $item->nama }}" 
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-select 
                name="semester" 
                label="Semester" 
                :options="['Ganjil' => 'Ganjil', 'Genap' => 'Genap']" 
                :selected="$item->semester" 
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                name="tahun_akademik" 
                label="Tahun Akademik" 
                placeholder="Contoh: 2024/2025" 
                value="{{ $item->tahun_akademik }}" 
                required 
            />
        </div>
        <div class="col-md-12 mb-3">
            <x-tabler.form-input 
                type="number" 
                name="tahun" 
                label="Tahun" 
                placeholder="Contoh: 2024" 
                value="{{ $item->tahun }}" 
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="date" 
                name="tanggal_mulai" 
                label="Tanggal Mulai" 
                value="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('Y-m-d') : '' }}" 
                required 
            />
        </div>
        <div class="col-md-6 mb-3">
            <x-tabler.form-input 
                type="date" 
                name="tanggal_selesai" 
                label="Tanggal Selesai" 
                value="{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('Y-m-d') : '' }}" 
                required 
            />
        </div>
    </div>
</x-tabler.form-modal>
