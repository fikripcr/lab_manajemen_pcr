@php
    $jenis = strtolower(trim($dokumen->jenis));
    $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
@endphp

<x-tabler.form-modal
    title="{{ $canProduceIndikator ? 'Tambah Poin / Kegiatan' : 'Tambah Sub-Dokumen' }}"
    route="{{ route('pemutu.dok-subs.store') }}"
    method="POST"
    submitText="Simpan"
>
    <input type="hidden" name="dok_id" value="{{ $dokumen->dok_id }}">
    
    <div class="mb-3">
        <x-tabler.form-input name="judul" label="Judul" id="judul" required="true" placeholder="{{ $canProduceIndikator ? 'Contoh: Standar Kompetensi Lulusan / Kegiatan Operasional 1' : 'Contoh: Misi 1' }}" />
    </div>
    
    @if($canProduceIndikator)
    <div class="mb-3">
        <x-tabler.form-checkbox 
            name="is_hasilkan_indikator" 
            label="Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?" 
            value="1" 
            :checked="$jenis === 'renop'" 
            switch 
        />
        <div class="text-muted small">Jika dicentang, poin ini nantinya akan memiliki tombol untuk input Indikator di halaman detail.</div>
    </div>
    @endif

    @if(!$canProduceIndikator || $jenis === 'renop')
    <div class="mb-3">
        <x-tabler.form-input type="number" id="seq" name="seq" label="Urutan" placeholder="Contoh: 1" class="mb-3" />
    </div>
    <x-tabler.form-textarea name="isi" id="isi" label="Isi Dokumen" rows="4" placeholder="Isi sub-dokumen..." />
    @endif
</x-tabler.form-modal>

