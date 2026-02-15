<div class="modal-header">
    <h5 class="modal-title">{{ $pageTitle }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemutu.dokumens.store') }}" method="POST" class="ajax-form">
    @csrf
    <div class="modal-body">
        <div class="mb-3">
            <label for="parent_id" class="form-label">Induk Dokumen (Parent)</label>
            @if(isset($parent) && $parent)
                <div class="form-control-plaintext">
                    @if(isset($parentDokSub) && $parentDokSub)
                        <div><strong>Turunan dari Poin:</strong> {{ $parentDokSub->judul }}</div>
                        <div class="small text-muted"><strong>Dokumen Induk:</strong> {{ $parent->judul }}</div>
                        <input type="hidden" name="parent_doksub_id" value="{{ $parentDokSub->doksub_id }}">
                    @else
                        <div class="fw-bold">
                            {{ $parent->judul }} <span class="badge badge-outline text-muted ms-2">{{ strtoupper($parent->jenis) }}</span>
                        </div>
                    @endif
                </div>
                <input type="hidden" name="parent_id" value="{{ $parent->dok_id }}">
            @else
                <div class="form-control-plaintext text-muted">
                    <em>Root Level (Dokumen Tertinggi)</em>
                </div>
                {{-- 
                <x-tabler.form-select id="parent_id" name="parent_id" label="Induk Dokumen" class="select2-offline" data-dropdown-parent="#modalAction">
                    <option value="">Tanpa Induk (Root)</option>
                     ...
                </x-tabler.form-select>
                --}}
            @endif
        </div>
        <div class="mb-3">
            <x-tabler.form-select 
                id="jenis" 
                name="jenis" 
                label="Jenis Dokumen"
                required="true" 
                class="select2-offline" 
                data-dropdown-parent="#modalAction"
                :options="$allowedTypes"
                :selected="null"
                placeholder="Pilih Jenis..."
            />
        </div>
        <div class="mb-3" id="judul-container">
            <x-tabler.form-input name="judul" id="judul" label="Judul Dokumen" placeholder="Contoh: Manual Mutu" required="true" />
        </div>
        <div class="mb-3">
            <x-tabler.form-input name="kode" id="kode" label="Kode Dokumen" placeholder="Contoh: MM-01" />
        </div>
        <div class="mb-3">
            <x-tabler.form-textarea name="isi" id="isi" label="Isi / Konten Dokumen" class="rich-text-editor" rows="10" />
        </div>
        {{-- Hidden fields for defaults --}}
        <input type="hidden" name="periode" value="{{ date('Y') }}">
    </div>
    <div class="modal-footer">
        <x-tabler.button type="button" text="Batal" class="btn-link link-secondary" data-bs-dismiss="modal" />
        <x-tabler.button type="submit" text="Simpan" />
    </div>
</form>

<script>
    // Auto-fill Judul based on Jenis
    $('#jenis').on('change', function() {
        const val = $(this).val();
        const text = $(this).find('option:selected').text().trim();
        const autoTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];

        if (autoTypes.includes(val)) {
            // Use the Label (e.g. "Visi") as the Title
            // But for RJP/Renstra usually we want just "RJP" or full? 
            // User said "jenis dokumen sudah mewakili".
            // Let's use the clean name.
            let cleanTitle = text;
            if(val === 'rjp') cleanTitle = 'Rencana Jangka Panjang (RJP)';
            if(val === 'renstra') cleanTitle = 'Rencana Strategis (Renstra)';
            if(val === 'renop') cleanTitle = 'Rencana Operasional (Renop)';
            
            // Or simple is better? "Visi", "Misi".
            if(val === 'visi') cleanTitle = 'Visi';
            if(val === 'misi') cleanTitle = 'Misi';

            $('#judul').val(cleanTitle);
            // $('#judul-container').hide(); // Allow editing title
        } else {
            $('#judul-container').show();
            if(!$('#judul').val()) {
                $('#judul').val(''); // Clear if empty
            }
        }
    });

    // Initial check (if only 1 option, it might be auto-selected)
    setTimeout(() => {
        if ($('#jenis').val()) {
            $('#jenis').trigger('change');
        } else if ($('#jenis option').length === 1) {
             // If only 1 option (excluding placeholder), select it
             $('#jenis option:eq(0)').prop('selected', true).trigger('change');
        }
    }, 100);
</script>

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
