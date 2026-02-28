<x-tabler.form-modal
    :title="'Set Tim Auditor: ' . $unit->name"
    :route="route('pemutu.tim-mutu.store-auditor', [$periode->encrypted_periodespmi_id, $unit->encrypted_org_unit_id])"
    method="POST"
>
    {{-- Ketua Auditor --}}
    <div class="mb-3">
        <label class="form-label">Ketua Auditor</label>
        <select name="ketua_auditor_id" id="ketua_auditor_id" class="form-select">
            @if($ketuaAuditor && $ketuaAuditor->pegawai)
                <option value="{{ encryptId($ketuaAuditor->pegawai_id) }}" selected>
                    {{ $ketuaAuditor->pegawai->nama }} ({{ $ketuaAuditor->pegawai->nip ?? '-' }})
                </option>
            @endif
        </select>
        <div class="form-text">Pilih Ketua Auditor untuk unit ini.</div>
    </div>

    {{-- Auditor --}}
    <div class="mb-3">
        <label class="form-label">Auditor</label>
        <select name="auditor_ids[]" id="auditor_ids" class="form-select" multiple>
            @foreach($auditor as $member)
                @if($member->pegawai)
                    <option value="{{ encryptId($member->pegawai_id) }}" selected>
                        {{ $member->pegawai->nama }} ({{ $member->pegawai->nip ?? '-' }})
                    </option>
                @endif
            @endforeach
        </select>
        <div class="form-text">Pilih satu atau lebih Auditor.</div>
    </div>

</x-tabler.form-modal>

<script>
    // Ensure Select2 is loaded before initializing
    if (typeof window.loadSelect2 === 'function') {
        window.loadSelect2().then(function() {
            // Helper for Select2 initialization
            function initSelect2(selector, placeholder) {
                $(selector).select2({
                    dropdownParent: $('#modalContent'),
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '{{ route('pemutu.tim-mutu.search-pegawai') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return { q: params.term };
                        },
                        processResults: function (data) {
                            return { results: data.results };
                        },
                        cache: true
                    }
                });
            }

            initSelect2('#ketua_auditor_id', 'Cari Ketua Auditor...');
            initSelect2('#auditor_ids', 'Cari Auditor...');
        });
    } else {
        console.error('window.loadSelect2 is not defined. Ensure tabler.js is loaded.');
    }
</script>
