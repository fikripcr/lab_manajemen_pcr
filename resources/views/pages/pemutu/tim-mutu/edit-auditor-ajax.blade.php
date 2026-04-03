<x-tabler.form-modal
    :title="'Set Tim Auditor: ' . $unit->name"
    :route="route('pemutu.tim-mutu.store-auditor', [$periode->encrypted_periodespmi_id, $unit->encrypted_org_unit_id])"
    method="POST"
>
    {{-- Ketua Auditor --}}

        @if($ketuaAuditor && $ketuaAuditor->pegawai)
            <option value="{{ encryptId($ketuaAuditor->pegawai_id) }}" selected>
                {{ $ketuaAuditor->pegawai->nama }} ({{ $ketuaAuditor->pegawai->nip ?? '-' }})
            </option>
        @endif
    </x-tabler.form-select>

    {{-- Auditor --}}

        @foreach($auditor as $member)
            @if($member->pegawai)
                <option value="{{ encryptId($member->pegawai_id) }}" selected>
                    {{ $member->pegawai->nama }} ({{ $member->pegawai->nip ?? '-' }})
                </option>
            @endif
        @endforeach
    </x-tabler.form-select>

</x-tabler.form-modal>

<script>

</script>
