<x-tabler.form-modal 
    title="Rencana Tindakan Perbaikan (RTP)" 
    route="{{ route('pemutu.ami.rtp-update', $indOrg->encrypted_indorgunit_id) }}" 
    method="POST"
    submitText="Simpan RTP"
    submitIcon="ti-device-floppy"
    data-reload="true">
    
    {{-- Info Temuan KTS --}}
    <div class="card bg-warning-lt border-0 border-start border-4 border-warning mb-4 shadow-sm">
        <div class="card-body p-3">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-warning text-white rounded-circle p-1 me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                    <i class="ti ti-alert-triangle fs-5"></i>
                </div>
                <div class="fw-bold text-warning-emphasis">Temuan KTS: {{ $indOrg->indikator->no_indikator }}</div>
            </div>
            
            <div class="text-dark-emphasis mb-3 ps-1">
                {{ $indOrg->indikator->indikator }}
            </div>

            @if($indOrg->ami_hasil_temuan_rekom)
                <div class="mt-2 pt-2 border-top border-warning-subtle">
                    <div class="small fw-bold mb-1">
                        <i class="ti ti-bulb me-1"></i>Rekomendasi Auditor:
                    </div>
                    <div class="small text-muted fst-italic ps-1">
                        {{ strip_tags($indOrg->ami_hasil_temuan_rekom) }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-3">
        <x-tabler.form-textarea 
            name="ami_rtp_isi" 
            label="Rencana Tindakan Perbaikan (RTP)" 
            placeholder="Tuliskan detail rencana tindakan untuk perbaikan..." 
            rows="5" 
            required="true"
            :value="$indOrg->ami_rtp_isi ?? ''"
            id="ami_rtp_isi"
        />
    </div>

    <div class="row">
        <div class="col-md-6">
            <x-tabler.form-input 
                type="date"
                name="ami_rtp_tgl_pelaksanaan" 
                label="Target Tanggal Pelaksanaan" 
                required="true"
                :value="$indOrg->ami_rtp_tgl_pelaksanaan ?? ''"
            />
        </div>
    </div>

</x-tabler.form-modal>

<script>
    if (window.loadHugeRTE) {
        window.loadHugeRTE('#ami_rtp_isi', {
            height: 200,
            menubar: false,
            statusbar: false,
            plugins: 'lists',
            toolbar: 'bold italic | bullist numlist',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }
</script>
