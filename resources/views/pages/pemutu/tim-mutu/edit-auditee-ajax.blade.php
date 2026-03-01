<x-tabler.form-modal
    :title="'Set Tim Auditee: ' . $unit->name"
    :route="route('pemutu.tim-mutu.store-auditee', [$periode->encrypted_periodespmi_id, $unit->encrypted_org_unit_id])"
    method="POST"
>
    {{-- Auditee --}}
    <x-tabler.form-select name="auditee_id" id="auditee_id" label="Auditee" help="Cari pegawai untuk dijadikan Auditee.">
        @if($auditee && $auditee->pegawai)
            <option value="{{ encryptId($auditee->pegawai_id) }}" selected>
                {{ $auditee->pegawai->nama }} ({{ $auditee->pegawai->nip ?? '-' }})
            </option>
        @endif
    </x-tabler.form-select>

    {{-- Anggota --}}
    <x-tabler.form-select name="anggota_ids" id="anggota_ids" multiple="true" label="Anggota Tim Mutu" help="Anda dapat memilih lebih dari satu anggota.">
        @foreach($anggota as $member)
            @if($member->pegawai)
                <option value="{{ encryptId($member->pegawai_id) }}" selected>
                    {{ $member->pegawai->nama }} ({{ $member->pegawai->nip ?? '-' }})
                </option>
            @endif
        @endforeach
    </x-tabler.form-select>

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

            initSelect2('#auditee_id', 'Cari Auditee...');
            initSelect2('#anggota_ids', 'Cari Anggota...');
        });
    } else {
        console.error('window.loadSelect2 is not defined. Ensure tabler.js is loaded.');
    }
</script>
