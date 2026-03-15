<x-tabler.form-modal 
    title="Pelaksanaan Tindakan Perbaikan (PTP)" 
    route="{{ route('pemutu.evaluasi-diri.ptp-update', encryptId($indOrg->indikorgunit_id)) }}" 
    method="POST"
    submitText="Simpan Pelaksanaan"
    submitIcon="ti-device-floppy"
    data-reload="true">
    
    <x-tabler.card class="bg-warning-lt border-0 border-start border-4 border-warning mb-4 shadow-sm">
        <x-tabler.card-body class="p-3">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-warning text-white rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                    <i class="ti ti-history fs-5"></i>
                </div>
                <div class="fw-bold text-warning-emphasis">Indikator KTS Tahun Lalu: {{ $indOrg->no_indikator }}</div>
            </div>
            
            <div class="text-dark-emphasis mb-3 ps-1">
                {{ $indOrg->pernyataan }}
            </div>

            @if($indOrg->ami_rtp_isi)
                <div class="mt-2 pt-2 border-top border-warning-subtle">
                    <div class="small fw-bold text-dark mb-1">
                        <i class="ti ti-bulb me-1"></i>Rencana Tindakan Perbaikan (RTP) Auditor:
                    </div>
                    <div class="small text-muted fst-italic ps-1">
                        {!! $indOrg->ami_rtp_isi !!}
                    </div>
                </div>
            @endif
        </x-tabler.card-body>
    </x-tabler.card>

    <div class="mb-3">
        <x-tabler.form-textarea 
            name="ed_ptp_isi" 
            label="Detail Pelaksanaan Tindakan Perbaikan" 
            placeholder="Jelaskan bagaimana tindakan perbaikan telah dilaksanakan..." 
            rows="5" 
            required="true"
            :value="$indOrg->ed_ptp_isi ?? ''"
            id="ed_ptp_isi"
        />
    </div>

</x-tabler.form-modal>

<script>
    if (window.loadHugeRTE) {
        window.loadHugeRTE('#ed_ptp_isi', {
            height: 250,
            menubar: false,
            statusbar: false,
            plugins: 'lists link table',
            toolbar: 'bold italic underline | bullist numlist | link table',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }
</script>
