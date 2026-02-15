@php
    $jenis = strtolower(trim($dokumen->jenis));
    $canProduceIndikator = in_array($jenis, ['standar', 'formulir', 'manual_prosedur', 'renop']);
@endphp

<div class="modal-header">
    <h5 class="modal-title">{{ $canProduceIndikator ? 'Tambah Poin / Kegiatan' : 'Tambah Sub-Dokumen' }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.dok-subs.store') }}" method="POST" class="ajax-form">
    @csrf
    <input type="hidden" name="dok_id" value="{{ $dokumen->dok_id }}">
    <div class="modal-body">
         <div class="mb-3">
               <x-tabler.form-input name="judul" label="Judul" id="judul" required="true" placeholder="{{ $canProduceIndikator ? 'Contoh: Standar Kompetensi Lulusan / Kegiatan Operasional 1' : 'Contoh: Misi 1' }}" />
          </div>
          
          @if($canProduceIndikator)
          <div class="mb-3">
              <label class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="is_hasilkan_indikator" value="1" {{ $jenis === 'renop' ? 'checked' : '' }}>
                  <span class="form-check-label">Hasilkan Indikator {{ ucfirst($jenis === 'renop' ? 'renop' : 'standar') }}?</span>
              </label>
              <div class="text-muted small">Jika dicentang, poin ini nantinya akan memiliki tombol untuk input Indikator di halaman detail.</div>
          </div>
          @endif

          @if(!$canProduceIndikator || $jenis === 'renop')
          <div class="mb-3">
              <label for="seq" class="form-label">Urutan</label>
              <input type="number" class="form-control" id="seq" name="seq" placeholder="Contoh: 1">
          </div>
          <x-tabler.form-textarea name="isi" id="isi" label="Isi Dokumen" rows="4" placeholder="Isi sub-dokumen..." />
          @endif
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>

