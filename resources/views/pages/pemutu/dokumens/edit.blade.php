<x-tabler.form-modal
    title="Ubah Dokumen"
    route="{{ route('pemutu.dokumens.update', $dokumen->encrypted_dok_id) }}"
    method="PUT"
    submitText="Simpan Perubahan"
>
    <div class="mb-3">
        <label for="parent_id" class="form-label">Induk Dokumen (Parent)</label>
        <x-tabler.form-select id="parent_id" name="parent_id" label="Induk Dokumen (Parent)" class="select2-offline" data-dropdown-parent="#modalAction">
            <option value="">Tanpa Induk (Root)</option>
            @foreach($dokumens as $d)
                <option value="{{ $d->encrypted_dok_id }}" {{ $dokumen->parent_id == $d->dok_id ? 'selected' : '' }}>
                    {{ $d->judul }}
                </option>
            @endforeach
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <label for="jenis" class="form-label required">Jenis Dokumen</label>
        <x-tabler.form-select id="jenis" name="jenis" label="Jenis Dokumen" required="true" class="select2-offline" data-dropdown-parent="#modalAction">
            <option value="">Pilih Jenis...</option>
            @if(isset($allowedTypes))
                @foreach($allowedTypes as $type)
                    <option value="{{ $type }}" {{ $dokumen->jenis == $type ? 'selected' : '' }}>
                        {{ match($type) {
                            'visi' => 'Visi',
                            'misi' => 'Misi',
                            'rjp' => 'RJP (Rencana Jangka Panjang)',
                            'renstra' => 'Renstra (Rencana Strategis)',
                            'renop' => 'Renop (Rencana Operasional)',
                            'standar' => 'Standar',
                            'formulir' => 'Formulir',
                            'manual_prosedur' => 'Manual Prosedur',
                            default => ucfirst($type)
                        } }}
                    </option>
                @endforeach
            @else
                <option value="{{ $dokumen->jenis }}" selected>{{ ucfirst($dokumen->jenis) }}</option>
            @endif
        </x-tabler.form-select>
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="judul" id="judul" label="Judul Dokumen" :value="$dokumen->judul" required="true" />
    </div>
    <div class="mb-3">
        <x-tabler.form-input name="kode" id="kode" label="Kode Dokumen" :value="$dokumen->kode" />
    </div>
    <div class="mb-3">
        <x-tabler.form-textarea name="isi" id="isi" label="Isi / Konten Dokumen" type="editor" height="400" rows="10" :value="$dokumen->isi" />
    </div>
    <input type="hidden" name="periode" value="{{ $dokumen->periode }}">
</x-tabler.form-modal>

