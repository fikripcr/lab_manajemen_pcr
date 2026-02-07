<div class="modal-header">
    <h5 class="modal-title">{{ $pageTitle }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('pemtu.dokumens.store') }}" method="POST" class="ajax-form">
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
                {{-- If Root, usually Visi --}}
                <div class="form-control-plaintext text-muted">
                    <em>Root Level (Dokumen Tertinggi)</em>
                </div>
                {{-- 
                <select class="form-select select2-offline" id="parent_id" name="parent_id" data-dropdown-parent="#modalAction">
                    <option value="">Tanpa Induk (Root)</option>
                     ...
                </select>
                --}}
            @endif
        </div>
        <div class="mb-3">
            <label for="jenis" class="form-label required">Jenis Dokumen</label>
            <select class="form-select select2-offline" id="jenis" name="jenis" required data-dropdown-parent="#modalAction">
                @if(count($allowedTypes) > 1)
                    <option value="">Pilih Jenis...</option>
                @endif
                @foreach($allowedTypes as $type)
                    <option value="{{ $type }}">
                        {{ match($type) {
                            'visi' => 'Visi',
                            'misi' => 'Misi',
                            'rjp' => 'RJP (Rencana Jangka Panjang)',
                            'renstra' => 'Renstra (Rencana Strategis)',
                            'renop' => 'Renop (Rencana Operasional)',
                            'standar' => 'Standar',
                            'formulir' => 'Formulir',
                            'dll' => 'Lain-lain',
                            default => ucfirst($type)
                        } }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3" id="judul-container">
            <label for="judul" class="form-label required">Judul Dokumen</label>
            <input type="text" class="form-control" id="judul" name="judul" required placeholder="Contoh: Manual Mutu">
        </div>
        <div class="mb-3">
            <label for="kode" class="form-label">Kode Dokumen</label>
            <input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh: MM-01">
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
            $('#judul-container').hide();
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
