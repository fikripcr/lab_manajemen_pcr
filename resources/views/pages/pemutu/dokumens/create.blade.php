<x-tabler.form-modal
    :title="$pageTitle"
    route="{{ route('pemutu.dokumens.store') }}"
    method="POST"
    submitText="Simpan"
>
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
        <x-tabler.form-textarea name="isi" id="isi" label="Isi / Konten Dokumen" type="editor" height="400" rows="10" />
    </div>
    <input type="hidden" name="periode" value="{{ date('Y') }}">
</x-tabler.form-modal>

<script>
    // Auto-fill Judul based on Jenis
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
            $('#judul-container').show();
            if(!$('#judul').val()) {
                $('#judul').val(''); // Clear if empty
            }
        }
    });

    setTimeout(() => {
        if ($('#jenis').val()) {
            $('#jenis').trigger('change');
        } else if ($('#jenis option').length === 1) {
             $('#jenis option:eq(0)').prop('selected', true).trigger('change');
        }
    }, 100);
</script>

