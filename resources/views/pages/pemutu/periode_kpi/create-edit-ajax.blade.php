@php
    $item = $periodeKpi ?? new \App\Models\Pemutu\PeriodeKpi();
    $isEdit = $item->exists;
    $route  = $isEdit 
        ? route('pemutu.periode-kpi.update', $item->encrypted_period_id) 
        : route('pemutu.periode-kpi.store');
    $method = $isEdit ? 'PUT' : 'POST';
    $title = $isEdit ? 'Edit Periode KPI' : 'Tambah Periode KPI';
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
                placeholder="Contoh: Periode KPI 2024" 
                value="{{ $item->nama }}" 
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
