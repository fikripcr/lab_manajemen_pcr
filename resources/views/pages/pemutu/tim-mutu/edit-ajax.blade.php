<x-tabler.form-modal
    :title="'Set Tim Mutu: ' . $unit->name"
    :route="route('pemutu.tim-mutu.store-unit', [$periode->periodespmi_id, $unit->orgunit_id])"
    method="POST"
>
    {{-- Auditee --}}
    <div class="mb-3">
        <label class="form-label">Auditee</label>
        <select name="auditee_id" id="auditee_id" class="form-select">
            @if($auditee && $auditee->pegawai)
                <option value="{{ $auditee->pegawai_id }}" selected>
                    {{ $auditee->pegawai->nama }} ({{ $auditee->pegawai->nip ?? '-' }})
                </option>
            @endif
        </select>
        <div class="form-text">Cari pegawai untuk dijadikan Auditee.</div>
    </div>

    {{-- Anggota --}}
    <div class="mb-3">
        <label class="form-label">Anggota Tim Mutu</label>
        <select name="anggota_ids[]" id="anggota_ids" class="form-select" multiple>
            @foreach($anggota as $member)
                @if($member->pegawai)
                    <option value="{{ $member->pegawai_id }}" selected>
                        {{ $member->pegawai->nama }} ({{ $member->pegawai->nip ?? '-' }})
                    </option>
                @endif
            @endforeach
        </select>
        <div class="form-text">Anda dapat memilih lebih dari satu anggota.</div>
    </div>

</x-tabler.form-modal>

<script>
    // Ensure Select2 is loaded before initializing
    if (typeof window.loadSelect2 === 'function') {
        window.loadSelect2().then(function() {
            // Initialize Select2 for Auditee
            $('#auditee_id').select2({
                dropdownParent: $('#modalContent'),
                placeholder: 'Cari Auditee...',
                allowClear: true,
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

            // Initialize Select2 for Anggota
            $('#anggota_ids').select2({
                dropdownParent: $('#modalContent'),
                placeholder: 'Cari Anggota...',
                allowClear: true,
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
        });
    } else {
        console.error('window.loadSelect2 is not defined. Ensure tabler.js is loaded.');
    }
</script>
