<x-tabler.form-modal
    :title="$pageTitle"
    route="{{ route('pemutu.dokumens.store') }}"
    method="POST"
>
    {{-- Parent Section: Hidden if parent is set, as per user request --}}
    @if(isset($parent) && $parent)
        <input type="hidden" name="parent_id" value="{{ $parent->encrypted_dok_id }}">
        @if(isset($parentDokSub) && $parentDokSub)
            <input type="hidden" name="parent_doksub_id" value="{{ $parentDokSub->encrypted_doksub_id }}">
        @endif
    @else
        <div class="mb-3">
            <label class="form-label">Induk Dokumen</label>
            <div class="form-control-plaintext text-muted">
                <em>Root Level (Dokumen Tertinggi)</em>
            </div>
        </div>
    @endif

    {{-- Jenis Dokumen --}}
    <div class="mb-3">
        @if(isset($fixedJenis) && $fixedJenis)
            <input type="hidden" name="jenis" value="{{ $fixedJenis }}">
            <input type="hidden" id="fixed-jenis-trigger" value="{{ $fixedJenis }}"> {{-- Trigger for JS --}}
            <label class="form-label">Jenis Dokumen</label>
            <div class="form-control-plaintext fw-bold">
                {{ $allowedTypes[$fixedJenis] ?? ucfirst($fixedJenis) }}
            </div>
        @else
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
        @endif
    </div>

    <div class="mb-3" id="judul-container">
        <x-tabler.form-input name="judul" id="judul" label="Judul Dokumen" placeholder="Contoh: Manual Mutu" required="true" />
    </div>
    
    <div class="mb-3">
        <x-tabler.form-input name="kode" id="kode" label="Kode Dokumen" placeholder="Contoh: MM-01" />
    </div>

    {{-- Editor removed as per request for initial form --}}
    
    <input type="hidden" name="periode" value="{{ date('Y') }}">
</x-tabler.form-modal>

<script>
    // Auto-fill Judul based on Jenis (Only for manual selection)
    $('#jenis').on('change', function() {
        const val = $(this).val();
        const text = $(this).find('option:selected').text().trim();
        const autoTypes = ['visi', 'misi', 'rjp', 'renstra', 'renop'];

        if (autoTypes.includes(val)) {
            let cleanTitle = text;
            if(val === 'rjp') cleanTitle = 'Rencana Jangka Panjang (RJP)';
            if(val === 'renstra') cleanTitle = 'Rencana Strategis (Renstra)';
            if(val === 'renop') cleanTitle = 'Rencana Operasional (Renop)';
            
            if(val === 'visi') cleanTitle = 'Visi';
            if(val === 'misi') cleanTitle = 'Misi';

            $('#judul').val(cleanTitle);
        } else {
            // Only clear/show if not manually edited? For now standard behavior
            if(!$('#judul').val()) {
                $('#judul').val(''); 
            }
        }
    });

    setTimeout(() => {
        // If Select2 exists and has 1 option, auto select
        if ($('#jenis').hasClass('select2-hidden-accessible') && $('#jenis option').length === 1) {
             $('#jenis option:eq(0)').prop('selected', true).trigger('change');
        }
    }, 100);
</script>

