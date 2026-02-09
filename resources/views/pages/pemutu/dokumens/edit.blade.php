<div class="modal-header">
    <h5 class="modal-title">Edit Dokumen</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.dokumens.update', $dokumen->dok_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="parent_id" class="form-label">Induk Dokumen (Parent)</label>
            <select class="form-select select2-offline" id="parent_id" name="parent_id" data-dropdown-parent="#modalAction">
                <option value="">Tanpa Induk (Root)</option>
                @foreach($dokumens as $d)
                    <option value="{{ $d->dok_id }}" {{ $dokumen->parent_id == $d->dok_id ? 'selected' : '' }}>
                        {{ $d->judul }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jenis" class="form-label required">Jenis Dokumen</label>
            <select class="form-select select2-offline" id="jenis" name="jenis" required data-dropdown-parent="#modalAction">
                <option value="">Pilih Jenis...</option>
                <option value="visi" {{ $dokumen->jenis == 'visi' ? 'selected' : '' }}>Visi</option>
                <option value="misi" {{ $dokumen->jenis == 'misi' ? 'selected' : '' }}>Misi</option>
                <option value="rjp" {{ $dokumen->jenis == 'rjp' ? 'selected' : '' }}>RJP (Rencana Jangka Panjang)</option>
                <option value="renstra" {{ $dokumen->jenis == 'renstra' ? 'selected' : '' }}>Renstra (Rencana Strategis)</option>
                <option value="renop" {{ $dokumen->jenis == 'renop' ? 'selected' : '' }}>Renop (Rencana Operasional)</option>
                <option value="standar" {{ $dokumen->jenis == 'standar' ? 'selected' : '' }}>Standar</option>
                <option value="formulir" {{ $dokumen->jenis == 'formulir' ? 'selected' : '' }}>Formulir</option>
                <option value="dll" {{ $dokumen->jenis == 'dll' ? 'selected' : '' }}>Lain-lain</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="judul" class="form-label required">Judul Dokumen</label>
            <input type="text" class="form-control" id="judul" name="judul" value="{{ $dokumen->judul }}" required>
        </div>
        <div class="mb-3">
            <label for="kode" class="form-label">Kode Dokumen</label>
            <input type="text" class="form-control" id="kode" name="kode" value="{{ $dokumen->kode }}">
        </div>
        {{-- Hidden fields to preserve or set defaults if needed --}}
        <input type="hidden" name="periode" value="{{ $dokumen->periode }}">
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan Perubahan" />
    </div>
</form>
