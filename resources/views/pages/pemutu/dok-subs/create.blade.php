<div class="modal-header">
    <h5 class="modal-title">Add Sub-Document to "{{ $dokumen->judul }}"</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.dok-subs.store') }}" method="POST" class="ajax-form">
    @csrf
    <input type="hidden" name="dok_id" value="{{ $dokumen->dok_id }}">
    <div class="modal-body">
        <div class="mb-3">
             <label for="judul" class="form-label required">Judul / Poin</label>
             <input type="text" class="form-control" id="judul" name="judul" required placeholder="Contoh: Misi 1">
         </div>
         <div class="mb-3">
             <label for="seq" class="form-label">Urutan</label>
             <input type="number" class="form-control" id="seq" name="seq" placeholder="Contoh: 1">
         </div>
         <div class="mb-3">
             <label for="isi" class="form-label">Isi Dokumen</label>
             <textarea class="form-control" id="isi" name="isi" rows="5" placeholder="Isi sub-dokumen..."></textarea>
         </div>
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Close" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>

