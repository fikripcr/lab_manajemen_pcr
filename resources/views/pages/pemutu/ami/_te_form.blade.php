<x-tabler.form-modal 
    title="Tinjauan Efektivitas (TE)" 
    route="{{ route('pemutu.ami.te-update', encryptId($indOrg->indikorgunit_id)) }}" 
    method="POST"
    submitText="Simpan Tinjauan"
    submitIcon="ti-device-floppy"
    data-reload="true">
    
    <x-tabler.card class="bg-info-lt border-0 border-start border-4 border-info mb-4 shadow-sm">
        <x-tabler.card-body class="p-3">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-info text-white rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                    <i class="ti ti-search fs-5"></i>
                </div>
                <div class="fw-bold text-info-emphasis">Indikator KTS Tahun Lalu: {{ $indOrg->no_indikator }}</div>
            </div>
            
            <div class="text-dark-emphasis mb-3 ps-1">
                {{ $indOrg->pernyataan }}
            </div>
            
            <div class="row g-2 ps-1">
                <div class="col-md-6">
                    <div class="bg-white p-2 rounded border border-warning-subtle h-100 shadow-sm">
                        <div class="small fw-bold text-warning mb-1">
                            <i class="ti ti-report-analytics me-1"></i>RTP (Auditor):
                        </div>
                        <div class="small text-muted">{!! $indOrg->ami_rtp_isi ?? '-' !!}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bg-white p-2 rounded border border-success-subtle h-100 shadow-sm">
                        <div class="small fw-bold text-success mb-1">
                            <i class="ti ti-run me-1"></i>PTP (Auditee):
                        </div>
                        <div class="small text-muted">{!! $indOrg->ed_ptp_isi ?? '-' !!}</div>
                    </div>
                </div>
            </div>
        </x-tabler.card-body>
    </x-tabler.card>

    <div class="mb-3">
        <x-tabler.form-textarea 
            name="ami_te_isi" 
            label="Hasil Tinjauan Efektivitas" 
            placeholder="Berikan tinjauan apakah tindakan perbaikan yang dilakukan sudah efektif atau perlu tindak lanjut lagi..." 
            rows="5" 
            required="true"
            :value="$indOrg->ami_te_isi ?? ''"
            id="ami_te_isi"
        />
    </div>

</x-tabler.form-modal>

<script>
    if (window.loadHugeRTE) {
        window.loadHugeRTE('#ami_te_isi', {
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
