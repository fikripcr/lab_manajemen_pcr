<div class="modal-header">
    <h5 class="modal-title">Ubah Dokumen</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.dokumens.update', $dokumen->dok_id) }}" method="POST" class="ajax-form">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="mb-3">
            <label for="parent_id" class="form-label">Induk Dokumen (Parent)</label>
            <x-tabler.form-select id="parent_id" name="parent_id" label="Induk Dokumen (Parent)" class="select2-offline" data-dropdown-parent="#modalAction">
                <option value="">Tanpa Induk (Root)</option>
                @foreach($dokumens as $d)
                    <option value="{{ $d->dok_id }}" {{ $dokumen->parent_id == $d->dok_id ? 'selected' : '' }}>
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
                    {{-- Fallback --}}
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
            <x-tabler.form-textarea name="isi" id="isi" label="Isi / Konten Dokumen" class="rich-text-editor" rows="10" :value="$dokumen->isi" />
        </div>
        {{-- Hidden fields to preserve or set defaults if needed --}}
        <input type="hidden" name="periode" value="{{ $dokumen->periode }}">
    </div>
    <div class="modal-footer">
        <x-tabler.button type="cancel" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" />
    </div>
</form>

<script>
    (function() {
        const initEditor = () => {
            if (window.loadHugeRTE) {
                window.loadHugeRTE('.rich-text-editor', {
                    height: 400,
                    menubar: true,
                    plugins: 'lists link table image code',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image | table | code'
                });
            }
        };

        if (typeof jQuery !== 'undefined' && jQuery('.modal').is(':visible')) {
            setTimeout(initEditor, 300);
        } else {
            document.addEventListener('DOMContentLoaded', initEditor);
            initEditor(); 
        }
    })();
</script>
