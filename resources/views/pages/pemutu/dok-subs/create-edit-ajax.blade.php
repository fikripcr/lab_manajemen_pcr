@php
    $isEdit = $dokSub->exists;
    $jenis = strtolower(trim($isEdit ? $dokSub->dokumen->jenis : $dokumen->jenis));
    $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
    
    $title = $isEdit ? "Edit: " . $dokSub->judul : ($canProduceIndikator ? 'Tambah Poin / Kegiatan' : 'Tambah Sub-Dokumen');
    $route = $isEdit ? route('pemutu.dok-subs.update', $dokSub->encrypted_doksub_id) : route('pemutu.dok-subs.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

<x-tabler.form-modal
    :title="$title"
    :route="$route"
    :method="$method"
    :submitText="$isEdit ? 'Simpan Perubahan' : 'Simpan'"
    :submitIcon="$isEdit ? 'ti-device-floppy' : 'ti-plus'"
>
    @if(!$isEdit)
        <input type="hidden" name="dok_id" value="{{ $dokumen->encrypted_dok_id }}">
    @endif

    <div class="row">
        <div class="{{ $isEdit ? 'col-md-8' : 'col-md-12' }}">
            <x-tabler.form-input 
                name="judul" 
                label="Judul" 
                id="judul" 
                :value="old('judul', $dokSub->judul)"
                required="true" 
                placeholder="{{ $canProduceIndikator ? 'Contoh: Standar Kompetensi Lulusan / Kegiatan Operasional 1' : 'Contoh: Misi 1' }}" 
            />
        </div>
        @if($isEdit)
        <div class="col-md-4">
            <x-tabler.form-input type="number" id="seq" name="seq" label="Urutan" :value="old('seq', $dokSub->seq)" />
        </div>
        @endif
    </div>
    
    @if($canProduceIndikator)
    <div class="mb-3 mt-3">
        <x-tabler.form-checkbox 
            name="is_hasilkan_indikator" 
            label="Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?" 
            value="1" 
            :checked="old('is_hasilkan_indikator', $isEdit ? $dokSub->is_hasilkan_indikator : ($jenis === 'renop'))" 
            switch 
        />
        <div class="text-muted small">Jika dicentang, poin ini nantinya akan memiliki tombol untuk input Indikator di halaman detail.</div>
    </div>
    @endif

    @if(!$canProduceIndikator || $jenis === 'renop' || $isEdit)
    <div class="mt-3">
        <x-tabler.form-textarea 
            :type="$isEdit ? 'editor' : 'textarea'"
            name="isi" 
            id="isi" 
            label="{{ $isEdit ? 'Konten / Isi Lengkap' : 'Isi Dokumen' }}" 
            :value="old('isi', $dokSub->isi)"
            rows="4" 
            placeholder="Isi sub-dokumen..." 
            :height="$isEdit ? 300 : null"
        />
    </div>
    @endif
</x-tabler.form-modal>
