@php
    $isEdit = $model->exists;
    $title = $isEdit ? 'Edit Rencana Operasional (Renop)' : 'Tambah Renop Baru';
    $route = $isEdit ? route('pemutu.renop.update', $model->encrypted_indikator_id) : route('pemutu.renop.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Update Renop' : 'Simpan Renop'"
>
    <input type="hidden" name="type" value="renop">
    
    <div class="mb-3">
        <x-tabler.form-textarea 
            name="indikator" 
            label="Indikator Renop" 
            :value="old('indikator', $model->indikator)"
            required="true"
            rows="3"
            placeholder="Contoh: Terlaksananya evaluasi kurikulum setiap 2 tahun"
        />
    </div>

    <div class="mb-3">
        <x-tabler.form-textarea 
            name="target" 
            label="Target Capaian" 
            :value="old('target', $model->target)"
            required="true"
            rows="2"
            placeholder="Contoh: 100%"
        />
    </div>

    <div class="row">
        <div class="col-md-8">
            <x-tabler.form-select 
                name="parent_id" 
                label="Indikator Induk (Opsional)" 
                type="select2"
                :options="['' => '-- Tanpa Induk --'] + $parents->toArray()"
                :selected="old('parent_id', $model->encrypted_parent_id ?? $model->parent_id)"
            />
        </div>
        <div class="col-md-4">
            <x-tabler.form-input 
                name="seq" 
                label="Urutan" 
                type="number" 
                :value="old('seq', $model->seq ?? 1)" 
            />
        </div>
    </div>
</x-tabler.form-modal>
