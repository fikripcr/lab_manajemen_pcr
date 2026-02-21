@php
    $isEdit = $model->exists;
    $title = $isEdit ? 'Ubah Sasaran Kinerja (KPI)' : 'Tambah Sasaran Kinerja';
    $route = $isEdit ? route('pemutu.kpi.update', $model->encrypted_indikator_id) : route('pemutu.kpi.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update KPI' : 'Simpan KPI'"
    size="lg"
>
    <!-- Hidden fields for consistency -->
    <input type="hidden" name="type" value="performa">
    
    <div class="row">
        <div class="col-12 mb-3">
            <x-tabler.form-select 
                name="parent_id" 
                label="Indikator Standar (Induk)" 
                type="select2" 
                :options="$parents->mapWithKeys(function($p) {
                    return [$p->encrypted_indikator_id => '[' . $p->no_indikator . '] ' . Str::limit($p->indikator, 150)];
                })->toArray()"
                :selected="old('parent_id', $model->encrypted_parent_id ?? $model->parent_id)" 
                placeholder="Cari indikator standar..." 
                required="true" 
            />
            <div class="form-hint">Pilih Indikator Standar sebagai acuan.</div>
        </div>

        <div class="col-md-4 mb-3">
             <x-tabler.form-input 
                name="no_indikator" 
                label="Kode / No. Sasaran" 
                type="text" 
                :value="old('no_indikator', $model->no_indikator)"
                placeholder="cth: KPI.01" 
            />
        </div>
        
        <div class="col-md-4 mb-3">
             <x-tabler.form-input 
                name="target" 
                label="Target" 
                type="text" 
                :value="old('target', $model->target)"
                placeholder="Nilai target" 
            />
        </div>

        <div class="col-md-4 mb-3">
             <x-tabler.form-input 
                name="unit_ukuran" 
                label="Satuan" 
                type="text" 
                :value="old('unit_ukuran', $model->unit_ukuran)"
                placeholder="%, Orang, Dokumen, dll" 
            />
        </div>
    
        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea 
                name="indikator" 
                label="Nama Sasaran Kinerja" 
                :value="old('indikator', $model->indikator)"
                rows="3" 
                required="true" 
                placeholder="Masukkan deskripsi sasaran kinerja"
            />
        </div>

        <div class="col-md-12 mb-3">
            <x-tabler.form-textarea 
                name="keterangan" 
                label="Definisi / Keterangan" 
                :value="old('keterangan', $model->keterangan)"
                rows="3" 
                type="editor"
                height="150"
            />
        </div>
    </div>
</x-tabler.form-modal>
